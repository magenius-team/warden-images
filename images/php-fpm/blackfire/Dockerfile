ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

RUN install-php-extensions blackfire


# Determine the architecture
RUN arch=$(uname -m) && \
    if [ "$arch" = "x86_64" ]; then arch="amd64"; fi && \
    if [ "$arch" = "aarch64" ]; then arch="arm64"; fi && \
    curl -A "Docker" -o /tmp/blackfire.tar.gz -L https://blackfire.io/api/v1/releases/cli/linux/$arch && \
    tar zxpf /tmp/blackfire.tar.gz -C /usr/local/bin && \
    chmod +x /usr/local/bin/blackfire \
    rm /tmp/blackfire.tar.gz

COPY blackfire/etc/php.d/*.ini /usr/local/etc/php/conf.d/

USER www-data
