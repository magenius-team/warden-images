package traefik_csp_merge

import (
	"context"
	"net/http"
	"strings"
)

// Config: use options from csp-merge.yml
type Config struct {
	// Domains
	Domains []string `json:"domains,omitempty" toml:"domains,omitempty" yaml:"domains,omitempty"`

	// Directives list
	Directives []string `json:"directives,omitempty" toml:"directives,omitempty" yaml:"directives,omitempty"`
}

func CreateConfig() *Config {
	return &Config{
		Domains:    []string{},
		Directives: []string{},
	}
}

type middleware struct {
	next            http.Handler
	directivesOrder []string
	addSources      []string
}

func New(_ context.Context, next http.Handler, cfg *Config, _ string) (http.Handler, error) {
	// normalize directives: trim + lowercase
	dirs := make([]string, 0, len(cfg.Directives))
	for _, d := range cfg.Directives {
		if v := strings.ToLower(strings.TrimSpace(d)); v != "" {
			dirs = append(dirs, v)
		}
	}
	// skip if no directives
	if len(dirs) == 0 || len(cfg.Domains) == 0 {
		return next, nil
	}

	return &middleware{
		next:            next,
		directivesOrder: dirs,
		addSources:      dedupeTrim(cfg.Domains),
	}, nil
}

func (m *middleware) ServeHTTP(rw http.ResponseWriter, req *http.Request) {
	w := &hdrWriter{
		ResponseWriter: rw,
		onWriteHeader: func(h http.Header) {
			vals := h.Values("Content-Security-Policy")
			if len(vals) > 0 {
				h.Set("Content-Security-Policy", mergeCSP(vals[0], m.directivesOrder, m.addSources))
			}

			vals = h.Values("Content-Security-Policy-Report-Only")
			if len(vals) > 0 {
				h.Set("Content-Security-Policy-Report-Only", mergeCSP(vals[0], m.directivesOrder, m.addSources))
			}
		},
	}
	m.next.ServeHTTP(w, req)

	// if we didn't write header yet, do it now
	if !w.wroteHeader {
		h := rw.Header()
		vals := h.Values("Content-Security-Policy")
		if len(vals) > 0 {
			h.Set("Content-Security-Policy", mergeCSP(vals[0], m.directivesOrder, m.addSources))
		}

		vals = h.Values("Content-Security-Policy-Report-Only")
		if len(vals) > 0 {
			h.Set("Content-Security-Policy-Report-Only", mergeCSP(vals[0], m.directivesOrder, m.addSources))
		}
	}
}

type hdrWriter struct {
	http.ResponseWriter
	onWriteHeader func(http.Header)
	wroteHeader   bool
}

func (w *hdrWriter) WriteHeader(statusCode int) {
	if !w.wroteHeader && w.onWriteHeader != nil {
		w.onWriteHeader(w.ResponseWriter.Header())
	}
	w.wroteHeader = true
	w.ResponseWriter.WriteHeader(statusCode)
}

func (w *hdrWriter) Write(b []byte) (int, error) {
	if !w.wroteHeader {
		w.WriteHeader(http.StatusOK)
	}
	return w.ResponseWriter.Write(b)
}

// mergeCSP: parses existing CSP, adds addSources to targetDirectives,
// avoids duplicates, reconstructs back (preserving directive order).
func mergeCSP(existing string, targetDirectives, addSources []string) string {
	existing = strings.TrimSpace(existing)
	if existing == "" || len(addSources) == 0 {
		return existing
	}

	type entry struct {
		name   string
		values []string
	}

	parts := splitSemicolons(existing)
	order := make([]string, 0, len(parts))
	table := make(map[string]*entry)

	for _, raw := range parts {
		raw = strings.TrimSpace(raw)
		if raw == "" {
			continue
		}
		toks := strings.Fields(raw)
		if len(toks) == 0 {
			continue
		}
		name := strings.ToLower(toks[0])
		vals := toks[1:]
		if e, ok := table[name]; ok {
			e.values = dedupePreserve(append(e.values, vals...))
		} else {
			table[name] = &entry{name: name, values: dedupePreserve(vals)}
			order = append(order, name)
		}
	}

	target := make(map[string]struct{}, len(targetDirectives))
	for _, d := range targetDirectives {
		if v := strings.ToLower(strings.TrimSpace(d)); v != "" {
			target[v] = struct{}{}
		}
	}
	add := dedupePreserve(addSources)

	for d := range target {
		if e, ok := table[d]; ok {
			cleaned := make([]string, 0, len(e.values))
			for _, v := range e.values {
				if strings.EqualFold(v, "'none'") {
					continue
				}
				cleaned = append(cleaned, v)
			}
			e.values = dedupePreserve(append(cleaned, add...))
		} else {
			order = append(order, d)
			table[d] = &entry{name: d, values: add}
		}
	}

	var b strings.Builder
	for i, name := range order {
		e := table[name]
		if len(e.values) == 0 {
			continue
		}
		if i > 0 && b.Len() > 0 {
			b.WriteString("; ")
		}
		b.WriteString(e.name)
		b.WriteByte(' ')
		b.WriteString(strings.Join(e.values, " "))
	}
	out := strings.TrimSpace(b.String())
	if out == "" {
		return existing
	}
	return out
}

func splitSemicolons(s string) []string {
	raw := strings.Split(s, ";")
	out := make([]string, 0, len(raw))
	for _, v := range raw {
		if t := strings.TrimSpace(v); t != "" {
			out = append(out, t)
		}
	}
	return out
}

func dedupePreserve(in []string) []string {
	seen := make(map[string]struct{}, len(in))
	out := make([]string, 0, len(in))
	for _, v := range in {
		v = strings.TrimSpace(v)
		if v == "" {
			continue
		}
		if _, ok := seen[v]; ok {
			continue
		}
		seen[v] = struct{}{}
		out = append(out, v)
	}
	return out
}

func dedupeTrim(in []string) []string {
	seen := make(map[string]struct{}, len(in))
	out := make([]string, 0, len(in))
	for _, v := range in {
		if t := strings.TrimSpace(v); t != "" {
			if _, ok := seen[t]; ok {
				continue
			}
			seen[t] = struct{}{}
			out = append(out, t)
		}
	}
	return out
}
