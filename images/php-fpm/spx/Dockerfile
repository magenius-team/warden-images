ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION} AS spx-builder
USER root

RUN apk --no-cache add g++ \
    autoconf \
    make

RUN cd /tmp \
    && git clone https://github.com/NoiseByNorthwest/php-spx.git \
    && git checkout release/latest \
    && cd php-spx \
    && phpize \
    && ./configure \
    && make \
    && sudo make install

ENV PHP_EXT_DIR=$(php-config --extension-dir)
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

COPY --from=spx-builder ${PHP_EXT_DIR}/spx.so ${PHP_EXT_DIR}/spx.so
COPY --from=spx-builder /usr/local/share/misc/php-spx/assets/web-ui /usr/local/share/misc/php-spx/assets/web-ui
COPY spx/etc/php.d/* /usr/local/etc/php/conf.d/

ENV SPX_ENABLED=1
USER www-data