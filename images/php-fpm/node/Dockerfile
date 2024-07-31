ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM node:${NODE_VERSION}-alpine AS node
RUN npm install -g grunt-cli gulp

ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

# Copy Node to PHP
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/lib/* /usr/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/share/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/lib/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/include/* /usr/local/include
RUN --mount=type=bind,from=node,source=/,target=/node ls -la /node/usr/local/bin &&  cp -ra /node/usr/local/bin/* /usr/local/bin && ls -la /usr/local/bin

# Copy Yarn
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/opt/* /opt && ls -la /opt
RUN $(which npm) -v

RUN apk update \
    && for pkg in ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libstdc++; do \
        if apk info -e $pkg; then apk del $pkg; fi; \
    done \
    && for pkg in ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libstdc++; do \
        if apk info -e $pkg; then apk add --update --no-cache $pkg; fi; \
    done

USER www-data
