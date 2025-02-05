ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION

FROM ${ENV_SOURCE_IMAGE}:${PHP_VERSION}
USER root

ARG NODE_VERSION

RUN arch=$(uname -m) && \
    if [ "$arch" = "x86_64" ]; then arch="x64"; fi && \
    if [ "$arch" = "aarch64" ]; then arch="arm64"; fi && \
    echo $arch && \
    echo https://nodejs.org/dist/latest-v${NODE_VERSION}.x/SHASUMS256.txt && \
    NODE_FILE=$(curl -fsSL https://nodejs.org/dist/latest-v${NODE_VERSION}.x/SHASUMS256.txt | grep "linux-$arch.tar.xz" | awk '{print $2}') && \
    echo $NODE_FILE && \
    curl -fsSL https://nodejs.org/dist/latest-v${NODE_VERSION}.x/$NODE_FILE | tar -xJf - -C /usr/local --strip-components=1

# Install global npm packages
RUN npm install -g grunt-cli gulp gulp-cli bower yarn

RUN apk update \
    && apk del ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++ \
    && apk add --update --no-cache ghostscript imagemagick gcc x265-libs libheif mariadb-client fish icu-libs libde265 libjxl libstdc++

USER www-data
