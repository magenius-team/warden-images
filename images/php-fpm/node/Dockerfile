ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM node:${NODE_VERSION}-alpine AS node
RUN npm install -g grunt-cli gulp

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

RUN php -v

# Copy Node to PHP
COPY --from=node /usr/lib /usr/lib

RUN php -v

COPY --from=node /usr/local/share /usr/local/share

RUN php -v

COPY --from=node /usr/local/lib /usr/local/lib

RUN php -v

COPY --from=node /usr/local/include /usr/local/include

RUN php -v

COPY --from=node /usr/local/bin /usr/local/bin

# Copy Yarn
COPY --from=node /opt /opt

RUN php -v

RUN apk update \
    && apk del ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --update --no-cache ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++

USER www-data
