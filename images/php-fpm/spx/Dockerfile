ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION} AS spx-builder
USER root

RUN apk --no-cache add g++ \
    autoconf \
    make \
    zlib-dev

RUN cd /tmp \
    && git clone https://github.com/NoiseByNorthwest/php-spx.git \
    && cd php-spx \
    && git checkout release/latest \
    && phpize \
    && ./configure \
    && make \
    && sudo make install

RUN PHP_EXT_DIR="$($(which php-config) --extension-dir)" \
    && ln -s "${PHP_EXT_DIR}" /tmp/php-ext-dir

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

RUN PHP_EXT_DIR="$($(which php-config) --extension-dir)" \
    && ln -s "${PHP_EXT_DIR}" /tmp/php-ext-dir

COPY --from=spx-builder /tmp/php-ext-dir/spx.so /tmp/php-ext-dir/spx.so
COPY --from=spx-builder /usr/local/share/misc/php-spx/assets/web-ui /usr/local/share/misc/php-spx/assets/web-ui
COPY spx/etc/php.d/* /usr/local/etc/php/conf.d/

RUN rm /tmp/php-ext-dir

ENV SPX_ENABLED=1
USER www-data
