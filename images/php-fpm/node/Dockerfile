ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION
ARG NODE_VERSION

FROM public.ecr.aws/docker/library/node:${NODE_VERSION}-alpine AS node
# Persist root distrust on Alpine branches with older CA packages.
RUN apk add --no-cache --upgrade ca-certificates ca-certificates-bundle \
    && sed -i \
        -e 's|^mozilla/emSign_ECC_Root_CA_-_G3\.crt$|!mozilla/emSign_ECC_Root_CA_-_G3.crt|' \
        -e 's|^mozilla/Comodo_AAA_Services_root\.crt$|!mozilla/Comodo_AAA_Services_root.crt|' \
        /etc/ca-certificates.conf \
    && update-ca-certificates
ENV NODE_OPTIONS="--use-openssl-ca" \
    SSL_CERT_FILE=/etc/ssl/certs/ca-certificates.crt
RUN npm install -g grunt-cli gulp

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

# Use the maintained system trust store instead of Node's bundled roots.
ENV NODE_OPTIONS="--use-openssl-ca" \
    SSL_CERT_FILE=/etc/ssl/certs/ca-certificates.crt

# Copy Node to PHP
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/lib/* /usr/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/share/* /usr/local/share
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/lib/* /usr/local/lib
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/include/* /usr/local/include
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/usr/local/bin/* /usr/local/bin

# Copy Yarn
RUN --mount=type=bind,from=node,source=/,target=/node cp -rna /node/opt/* /opt
RUN npm -v

RUN apk update \
    && apk del ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --update --no-cache ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --no-cache --upgrade ca-certificates ca-certificates-bundle \
    && sed -i \
        -e 's|^mozilla/emSign_ECC_Root_CA_-_G3\.crt$|!mozilla/emSign_ECC_Root_CA_-_G3.crt|' \
        -e 's|^mozilla/Comodo_AAA_Services_root\.crt$|!mozilla/Comodo_AAA_Services_root.crt|' \
        /etc/ca-certificates.conf \
    && update-ca-certificates

USER www-data
