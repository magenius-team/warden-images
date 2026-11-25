FROM node:25-bookworm-slim@sha256:81db02c4b671288a03915da9534dbd54f96d0e7c24d80ccc54f5b36b2e684370

USER root

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates chromium fonts-liberation libnss3-tools tini \
    && rm -rf /var/lib/apt/lists/* \
    && groupmod --new-name chrome node \
    && usermod --login chrome --home /home/chrome --move-home node \
    && mkdir -p /var/www/html /home/chrome/.pki/nssdb \
    && certutil -N --empty-password -d sql:/home/chrome/.pki/nssdb \
    && chown -R 1000:1000 /var/www/html /home/chrome

WORKDIR /var/www/html
COPY bin/generate.sh /usr/bin/generate
COPY bin/bundle.sh /usr/bin/bundle
COPY bin/entrypoint.sh /usr/local/bin/magepack-entrypoint
COPY validate-ca.cjs /usr/local/lib/magepack/validate-ca.cjs
COPY apply-tls-patch.cjs NOTICE.md /usr/local/share/magepack-remediation/

ARG MAGEPACK_VERSION
RUN test -n "${MAGEPACK_VERSION}" \
    && npm install -g "magepack@^${MAGEPACK_VERSION}" \
    && node /usr/local/share/magepack-remediation/apply-tls-patch.cjs \
        /usr/local/lib/node_modules/magepack \
    && cp /usr/local/share/magepack-remediation/NOTICE.md \
        /usr/local/lib/node_modules/magepack/WARDEN-TLS-NOTICE.md \
    && ln -s /usr/local/bin/magepack /usr/bin/magepack \
    && chmod 0755 /usr/bin/generate /usr/bin/bundle /usr/local/bin/magepack-entrypoint \
    && npm cache clean --force

ENTRYPOINT ["/usr/bin/tini", "--", "/usr/local/bin/magepack-entrypoint"]
CMD ["tail", "-f", "/dev/null"]

USER chrome
