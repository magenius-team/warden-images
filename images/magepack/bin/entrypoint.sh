#!/bin/sh
set -eu

# This is the image user's NSS database, never the host's trust store.
database=sql:/home/chrome/.pki/nssdb
nickname=warden-mounted-ca

# Validate the database first; do not hide corrupt/unreadable database errors.
certutil -L -d "$database" >/dev/null
if certutil -L -d "$database" -n "$nickname" >/dev/null 2>&1; then
    certutil -D -d "$database" -n "$nickname"
fi

if [ "${NODE_EXTRA_CA_CERTS+x}" = x ]; then
    # Node itself only warns on invalid extra CA files; explicitly fail instead.
    # Disable loading the requested file in this validation process only.
    env -u NODE_EXTRA_CA_CERTS node /usr/local/lib/magepack/validate-ca.cjs "$NODE_EXTRA_CA_CERTS"
    certutil -A -d "$database" -n "$nickname" -t 'C,,' -a -i "$NODE_EXTRA_CA_CERTS"
    certutil -L -d "$database" -n "$nickname" >/dev/null
fi

exec "$@"
