ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
ARG XDEBUG_TYPE="xdebug-stable"
USER root

RUN install-php-extensions ${XDEBUG_TYPE}

COPY xdebug3/etc/*.ini /usr/local/etc/php/
COPY xdebug3/etc/php.d/* /usr/local/etc/php/conf.d/

RUN if [[ $(printf "8.0\n${PHP_VERSION}" | sort -g | head -n1) == "8.0" ]]; \
    then mv /usr/local/etc/php/conf.d/15-xdebug3.2.ini.template /usr/local/etc/php/conf.d/15-xdebug.ini; \
    else mv /usr/local/etc/php/conf.d/15-xdebug3.ini.template /usr/local/etc/php/conf.d/15-xdebug.ini; fi

USER www-data
