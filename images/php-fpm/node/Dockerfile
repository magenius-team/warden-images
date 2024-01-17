ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM node:${NODE_VERSION}-alpine AS node
RUN npm install -g grunt-cli gulp

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

# Copy Node to PHP
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/lib/* /usr/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/share/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/lib/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/include/* /usr/local/include
RUN --mount=type=bind,from=node,source=/,target=/node ls -la /node/usr/local/bin &&  cp -rna /node/usr/local/bin/* /usr/local/bin && ls -la /usr/local/bin

# Copy Yarn
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/opt/* /opt
RUN npm -v

RUN apk update \
    && apk del ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --update --no-cache ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++

USER www-data
