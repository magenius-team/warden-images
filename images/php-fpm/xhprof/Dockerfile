ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

RUN install-php-extensions xhprof

RUN mkdir -p /usr/local/etc/php/runs/
COPY xhprof/etc/runs/* /usr/local/etc/php/runs/
COPY xhprof/etc/php.d/15-xhprof-buggregator.ini /usr/local/etc/php/conf.d/15-xhprof-buggregator.ini

USER www-data
