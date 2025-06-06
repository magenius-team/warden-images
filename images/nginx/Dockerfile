ARG NGINX_VERSION
FROM public.ecr.aws/docker/library/nginx:${NGINX_VERSION}-alpine
RUN apk add --no-cache bash shadow

ENV NGINX_UPSTREAM_HOST           php-fpm
ENV NGINX_UPSTREAM_PORT           9000
ENV NGINX_UPSTREAM_BLACKFIRE_HOST php-blackfire
ENV NGINX_UPSTREAM_BLACKFIRE_PORT 9000
ENV NGINX_UPSTREAM_DEBUG_HOST     php-debug
ENV NGINX_UPSTREAM_DEBUG_PORT     9000
ENV NGINX_UPSTREAM_SPX_HOST       php-spx
ENV NGINX_UPSTREAM_SPX_PORT       9000
ENV NGINX_UPSTREAM_XHPROF_HOST    php-xhprof
ENV NGINX_UPSTREAM_XHPROF_PORT    9000
ENV NGINX_ROOT                    /var/www/html
ENV NGINX_PUBLIC                  ''
ENV NGINX_TEMPLATE                application.conf
ENV XDEBUG_CONNECT_BACK_HOST      '""'

COPY etc/nginx/fastcgi_params /etc/nginx/fastcgi_params.template
COPY etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.template
COPY etc/nginx/available.d/*.conf /etc/nginx/available.d/

COPY etc/nginx/fastcgi-params.sh /usr/local/bin/fastcgi-params.sh
RUN chmod +x /usr/local/bin/fastcgi-params.sh

CMD envsubst '${NGINX_UPSTREAM_HOST} ${NGINX_UPSTREAM_PORT} \
              ${NGINX_UPSTREAM_DEBUG_HOST} ${NGINX_UPSTREAM_DEBUG_PORT} \
              ${NGINX_UPSTREAM_XHPROF_HOST} ${NGINX_UPSTREAM_XHPROF_PORT} \
              ${NGINX_UPSTREAM_SPX_HOST} ${NGINX_UPSTREAM_SPX_PORT} \
              ${NGINX_UPSTREAM_BLACKFIRE_HOST} ${NGINX_UPSTREAM_BLACKFIRE_PORT} \
              ${NGINX_ROOT} ${NGINX_PUBLIC} ${NGINX_TEMPLATE}' \
        < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf \
    && envsubst '${XDEBUG_CONNECT_BACK_HOST}' \
        < /etc/nginx/fastcgi_params.template > /etc/nginx/fastcgi_params \
    && /usr/local/bin/fastcgi-params.sh \
    && nginx -g "daemon off;"

WORKDIR /var/www/html
