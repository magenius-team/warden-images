# Magepack image

## Source remediation (not yet runtime-qualified)

The image uses official `node:22-bookworm-slim` and Debian security Chromium
for native Linux amd64 and arm64, not the old Alpine Chromium 124 image.
The pinned Node manifest index is:
`sha256:be979e5523c76fa4d5ca4ebd3099078f220aa47ec39c48ad5b5df9314debd6eb`.
Verified platform manifests:

- amd64: `sha256:d20a7b8b4184bf74fe77499b5460a3d69e582bc733d50287e40c3fe791764851`
- arm64/v8: `sha256:dbfc182a6fa77abdfded8170166d26879e4ed30401683c2e46f1acd5ba55805d`

At source preparation, Debian bookworm-security package indexes advertised
Chromium `156.0.8078.108-1~deb12u1` for both architectures. The build deliberately
installs the current distro package rather than pinning an aging browser.
Rebuild with fresh apt layers for security updates; periodically review the
Node digest and Debian support. Record actual versions and image digests on
each release. Source checks are not proof of a built image's browser version.

References:

- https://github.com/nodejs/docker-node/blob/main/22/bookworm-slim/Dockerfile
- https://deb.debian.org/debian-security/dists/bookworm-security/main/binary-amd64/Packages.xz
- https://deb.debian.org/debian-security/dists/bookworm-security/main/binary-arm64/Packages.xz
- https://github.com/puppeteer/puppeteer/blob/v2.1.1/lib/Launcher.js
- https://github.com/SeleniumHQ/docker-selenium/blob/trunk/charts/selenium-grid/certs/add-cert-helper.sh
- https://chromium.googlesource.com/chromium/src/+/main/docs/linux/cert_management.md

The existing workflow still obtains `MAGEPACK_VERSION` from the GitHub
`MAGEPACK_MATRIX` variable. Do not hardcode versions in the workflow or alter
the variable's existing escaping convention. There is no Dockerfile default:
local builds must pass the authorized matrix version explicitly.
With the existing `2.11` matrix entry, npm resolves the release range; the
build-time patch then requires exactly reviewed Magepack 2.11.2, Puppeteer
2.1.1, and the original source hash. Any mismatch fails the build, including
future npm releases, rather than silently retaining disabled TLS checks.

The patch changes only Magepack's HTTPS-error option to `false`. No runtime
monkeypatch, certificate bypass, browser fallback, or replacement Puppeteer
API is installed. Puppeteer 2.1.1 is old: its compatibility with the current
Chromium CDP requires real runtime qualification on **both** platforms.
Do not release on the strength of static checks alone.

Upstream `--no-sandbox` and `--disable-dev-shm-usage` remain unchanged.
This is **not browser sandbox hardening**. Do not add privileged mode,
`SYS_ADMIN`, host security changes, or TLS bypasses to make verification pass.

## Runtime contract and explicit private CA

The image keeps user/group `chrome` at UID/GID 1000, writable
`/var/www/html`, the existing `generate` argument forwarding and `bundle`
behavior, `/usr/bin/magepack`, and the idle `tail -f /dev/null` default.
Bind-mounted project directories must already permit UID 1000 writes:
the entrypoint never recursively changes ownership of host files.
Tini forwards signals and reaps child processes.

For a private CA, explicitly mount **one public PEM CA certificate** read-only
and set the container environment:

```yaml
environment:
  NODE_EXTRA_CA_CERTS: /run/magepack-ca/root.crt
volumes:
  - /explicit/path/to/public-root.crt:/run/magepack-ca/root.crt:ro
```

No implicit host certificate discovery occurs. Do not mount private keys,
credentials, the host home, Docker socket, or entire host trust directories.
The entrypoint validates the absolute path, regular/readable file, single
PEM certificate, CA constraint and validity period; requested validation or
NSS import failure prevents the command from starting.
It imports with server-CA trust `C,,` into
`sql:/home/chrome/.pki/nssdb` and leaves `NODE_EXTRA_CA_CERTS` pointing to the
same explicit file for Node HTTPS, including subsequent container execs.
The Selenium helper above documents the NSS import mechanism and configurable
trust attributes; its broader default trust is intentionally not copied.
Chromium's certificate-management documentation specifies `C,,` for server
CA trust. Since M146 its new default database path is
`$HOME/.local/share/pki/nssdb`, but an existing `$HOME/.pki/nssdb` takes
precedence; this image creates that existing database at build time.
No system trust update, startup network access, or host HOME change is needed.
The image user's passwd home is `/home/chrome`; the Dockerfile and entrypoint
do not override the `HOME` environment variable.

Keep the certificate mount immutable for the container lifetime. Recreate the
container after CA changes; do not persist the NSS home/database or override
HOME/user. On entrypoint restart the managed CA nickname is removed before
optional reimport, so omitting the variable does not retain that managed CA.
Starting with an overridden entrypoint bypasses initialization and is unsupported.
Other deliberate Node/browser TLS overrides are unsupported.

## Required qualification before publication

Build/push/dispatch are separate authorized steps, not part of this source
change. Before a build, confirm the GitHub matrix value, registry access,
current Node index/platform digests and Debian security packages. Builds need
network access to official image, Debian and npm registries. Use isolated
builders and no host credentials in build contexts.

After build authorization, build both architectures and record Node,
Chromium, Magepack, Puppeteer and package versions. Run bounded checks on native
amd64 and arm64 workers (emulation alone does not qualify native runtime).
Use isolated local fixtures with no external network or production credentials:

1. Confirm UID/GID 1000, workdir RW, `generate --help`, `bundle`, idle command
   and SIGTERM/child cleanup. Exercise actual generate and bundle on a minimal
   local Magento fixture; confirm nonempty usable configuration and bundles,
   not just successful process launch. Check files remain UID 1000 owned.
2. Run Node HTTPS and Puppeteer navigation against local HTTPS fixtures:
   a valid chain signed by the explicit CA succeeds only with that CA mounted;
   without the CA, the untrusted chain fails.
3. Even with the correct CA mounted, wrong-hostname and expired leaf
   certificates must fail in **both** clients. An unrelated/self-signed chain
   must fail. Assert certificate errors, no successful page/HTTPS response,
   and no generated usable output for invalid navigation.
4. Missing, unreadable, empty, malformed, multi-certificate, non-CA, expired
   CA, not-yet-valid CA, and key-containing input must stop startup. A denied
   NSS database write/import must stop startup. Restart without the CA setting
   and verify the previously managed CA is no longer trusted.
5. Exercise actual Magepack generate against trusted and untrusted TLS
   fixture URLs. A standalone browser test alone cannot prove Magepack uses
   verification. No `ignoreHTTPSErrors`, `--ignore-certificate-errors`,
   `NODE_TLS_REJECT_UNAUTHORIZED=0`, authentication bypass, or special security
   capability is allowed in the tests.

If the old Puppeteer API fails with current Chromium, stop and request a
separately reviewed compatibility change; never fall back to an old browser.
Review [the modification notice](../images/magepack/NOTICE.md): the upstream
OSL-2.0 metadata versus OSL-3.0 license-text discrepancy requires clarification
before publication. Original license files, metadata, modified source and
attribution are retained; no license version is silently substituted.
