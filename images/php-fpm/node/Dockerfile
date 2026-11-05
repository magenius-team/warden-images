ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM public.ecr.aws/docker/library/node:${NODE_VERSION}-alpine AS node
COPY --chmod=0755 update-ca-trust /usr/local/bin/
RUN update-ca-trust
ENV SSL_CERT_FILE=/etc/ssl/certs/ca-certificates.crt
RUN mkdir -p /usr/local/libexec \
    && mv /usr/local/bin/node /usr/local/libexec/node
COPY --chmod=0755 node-launcher /usr/local/bin/node
RUN npm install -g grunt-cli gulp

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

# Use the maintained system trust store instead of Node's bundled roots.
ENV SSL_CERT_FILE=/etc/ssl/certs/ca-certificates.crt

# Copy Node to PHP
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/lib/* /usr/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/share/* /usr/local/share
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/lib/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/include/* /usr/local/include
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/bin/* /usr/local/bin
COPY --from=node /usr/local/libexec/node /usr/local/libexec/node
COPY --chmod=0755 node-launcher /usr/local/bin/node

# Copy Yarn
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/opt/* /opt
RUN npm -v

RUN apk update \
    && apk del ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --update --no-cache ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && update-ca-trust

USER www-data
