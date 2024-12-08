ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

RUN install-php-extensions xhprof

COPY xhprof/etc/php.d/15-xhprof.ini /usr/local/etc/php/conf.d/15-xhprof.ini

USER www-data
