ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM alpine:3.15 as alpine
RUN apk add --no-cache --repository https://nl.alpinelinux.org/alpine/edge/main libstdc++

FROM node:${NODE_VERSION}-alpine AS node
RUN npm install -g grunt-cli gulp

ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

# Conditional copy from libstdc if PHP_VERSION is 7.2
RUN if [ $(printf "7.2\n${PHP_VERSION}" | sort -g | head -n1) = "7.2" ]; then \
        --mount=type=bind,from=alpine,source=/,target=/libstdc cp -ra /libstdc/usr/lib/libstdc++.so.6.0.29 /usr/lib/libstdc++.so.6.0.29 \
        && ln -s /usr/lib/libstdc++.so.6.0.29 /usr/lib/libstdc++.so.6; \
    fi

# Copy Node to PHP
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/lib/* /usr/lib && ls -la /usr/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/share/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/lib/* /usr/local/lib && ls -la /usr/local/lib/node_modules/npm/bin
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/usr/local/include/* /usr/local/include && ls -la /usr/local/include
RUN --mount=type=bind,from=node,source=/,target=/node ls -la /node/usr/local/bin &&  cp -ra /node/usr/local/bin/* /usr/local/bin && ls -la /usr/local/bin
RUN npm -v

# Copy Yarn
RUN --mount=type=bind,from=node,source=/,target=/node cp -ra /node/opt/* /opt && ls -la /opt

RUN apk update \
    && for pkg in ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libstdc++; do \
        if apk info -e $pkg; then apk del $pkg; fi; \
    done \
    && for pkg in ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libstdc++; do \
        if apk info -e $pkg; then apk add --update --no-cache $pkg; fi; \
    done

USER www-data
