ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

COPY xhprof/etc/php.d/* /usr/local/etc/php/conf.d/

USER www-data
