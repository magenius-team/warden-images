ARG ENV_SOURCE_IMAGE
ARG PHP_VERSION

FROM public.ecr.aws/docker/library/golang:alpine AS mhs-builder
RUN go install github.com/mailhog/mhsendmail@latest

FROM axllent/mailpit:latest AS mps-builder

FROM ${ENV_SOURCE_IMAGE:-php}:${PHP_VERSION}-fpm-alpine
USER root
ARG PHP_VERSION

ENV MAIL_SERVICE_PORT=1025

# PHP Extension Installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Copy sendmail service to PHP
COPY --from=mhs-builder /go/bin/mhsendmail /usr/local/bin/mhsendmail
COPY --from=mps-builder /mailpit /usr/local/bin/mpsendmail

# Install PHP Extensions required by Magento OS, Adobe Commerce, and the UCT (pcntl)
RUN install-php-extensions \
    apcu \
    amqp \
    bcmath \
    calendar \
    exif \
    ftp \
    gd \
    intl \
    imap \
    mysqli \
    pcntl \
    pdo_mysql \
    redis \
    soap \
    sockets \
    sodium \
    xsl \
    zip \
    ssh2

# Install Imagick for php < 8.3 only due to issue https://github.com/Imagick/imagick/issues/640
RUN if [[ "${PHP_VERSION}" != "8.3" ]]; \
    then install-php-extensions imagick; fi

# Install helpful utilities
RUN apk --no-cache add \
    jq \
    bash \
    patch \
    pv \
    procps \
    nano \
    vim \
    mariadb-client \
    sudo \
    busybox-suid \
    gettext \
    ca-certificates \
    shadow \
    git \
    rsync \
    bash-completion \
    zsh \
    git-zsh-completion \
    fish \
    pwgen \
    openssh \
    socat

# Add Cron
RUN apk --no-cache add busybox-openrc || apk --no-cache add busybox-initscripts

# Bring in the files
COPY docker-entrypoint /usr/local/bin/
COPY etc/profile.d/*.sh /etc/profile.d/
COPY etc/*.ini /usr/local/etc/php/
COPY etc/php.d/*.ini /usr/local/etc/php/conf.d/
COPY etc/php.d/05-additions.ini.template /usr/local/etc/php/conf.d/
COPY etc/php.d /tmp/php.d

# If PHP < 8.1 then copy 10-opcache.ini from 74 folder else from 8x
RUN if [[ $(printf "8.1\n${PHP_VERSION}" | sort -g | head -n1) != "8.1" ]]; \
    then cp /tmp/php.d/74/*.ini /usr/local/etc/php/conf.d/; \
    else cp /tmp/php.d/8x/*.ini /usr/local/etc/php/conf.d/; fi

# Install Composer
COPY --from=public.ecr.aws/docker/library/composer:1 /usr/bin/composer /usr/bin/composer1
RUN sleep 5
COPY --from=public.ecr.aws/docker/library/composer:2.2 /usr/bin/composer /usr/bin/composer2lts
RUN sleep 5
COPY --from=public.ecr.aws/docker/library/composer:2 /usr/bin/composer /usr/bin/composer2

# If PHP < 8.2
RUN if [[ $(printf "8.2\n${PHP_VERSION}" | sort -g | head -n1) != "8.2" ]]; \
    then install-php-extensions mcrypt; fi

# If PHP >= 7.1
RUN if [[ $(printf "7.1\n${PHP_VERSION}" | sort -g | head -n1) == "7.1" ]]; \
    then apk --no-cache add mariadb-connector-c; fi

# If PHP >= 7.2
RUN if [[ $(printf "7.2\n${PHP_VERSION}" | sort -g | head -n1) == "7.2" ]]; \
    then apk --no-cache add composer-zsh-completion; fi

# Enable command completion in Zsh
RUN mkdir -p /etc/zsh/zshrc.d  \
    && echo "autoload -U compinit; compinit" > /etc/zsh/zshrc.d/completions.zsh \
    && ln -s /usr/bin/fish /bin/fish

# Install Oh My Zsh including the following Plugins: git, composer and n98-magerun
RUN sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" \
    && sed -i 's/plugins=(git)/plugins=(git composer n98-magerun)/g' /root/.zshrc

# Configure www-data user as primary php-fpm user for better local dev experience
RUN groupmod -g 1000 www-data && usermod -u 1000 www-data \
    && chmod 0755 ~www-data \
    && mkdir -p /var/www/html \
    && chown www-data:www-data /var/www/html \
    && echo "www-data ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers.d/nopasswd

# Override stop signal to stop process gracefully
# https://github.com/php/php-src/blob/17baa87faddc2550def3ae7314236826bc1b1398/sapi/fpm/php-fpm.8.in#L163
STOPSIGNAL SIGQUIT

ENTRYPOINT ["docker-entrypoint"]
WORKDIR /var/www/html
USER www-data
EXPOSE 9000
CMD ["php-fpm"]
