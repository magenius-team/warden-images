<?php
if (PHP_SAPI === 'cli') {
    return;
}

$domain = getenv('TRAEFIK_DOMAIN') ?? false;
if (!$domain) {
    return;
}

$tokens = [
    "https://*.{$domain}",
    "http://*.{$domain}"
];

/**
 * Appends tokens to CSP header string
 *
 * @param string $policy
 * @param array $tokens
 * @return string
 */
function csp_append_tokens(string $policy, array $tokens): string
{
    $policies = [
        'default-src',
        'script-src',
        'style-src',
        'img-src',
        'font-src',
        'connect-src',
        'media-src',
    ];

    $out = $policy;
    foreach ($policies as $policy) {
        if (preg_match('/\b' . preg_quote($policy, '/') . '\b\s+[^;]*/i', $out, $m)) {
            $seg = $m[0];
            foreach ($tokens as $t) {
                if (strpos($seg, $t) === false) {
                    $seg .= ' ' . $t;
                }
            }
            $out = str_replace($m[0], $seg, $out);
        } else {
            $out = rtrim($out);
            $out .= (str_ends_with($out, ';') ? '' : ';') . ' ' . $policy . ' ' . implode(' ', $tokens) . ';';
        }
    }
    return $out;
}

/**
 * Register header callback
 */
header_register_callback(function () use ($tokens) {
    // Only process text and application/json
    $contentType = null;
    foreach (headers_list() as $h) {
        if (stripos($h, 'Content-Type:') === 0) {
            $contentType = trim(substr($h, 13));
            break;
        }
    }
    if ($contentType && stripos($contentType, 'text/') !== 0 && stripos($contentType, 'application/json') !== 0) {
        return;
    }

    $targets = ['Content-Security-Policy', 'Content-Security-Policy-Report-Only'];
    $values = [];
    foreach (headers_list() as $h) {
        foreach ($targets as $t) {
            if (stripos($h, $t . ':') === 0) {
                $values[$t] = trim(substr($h, strlen($t) + 1));
            }
        }
    }

    foreach ($targets as $t) {
        if (empty($values[$t])) {
            continue;
        }
        $new = csp_append_tokens($values[$t], $tokens);
        if ($new !== $values[$t]) {
            header_remove($t);
            header($t . ': ' . $new, true);
        }
    }
});
