FROM python:3.6-alpine3.7

RUN ln -s /usr/bin/pip /usr/bin/pip3

RUN pip install --upgrade pip

# Upgrade and install basic Python dependencies
# This block added because of the trouble installing gevent on many systems
# https://hub.docker.com/r/openwhisk/dockerskeleton/~/dockerfile/
RUN apk update \
    && apk add supervisor \
    && apk add --update py2-pip \
    && apk add --no-cache bash \
    && apk add --no-cache --virtual .build-deps bzip2-dev gcc libc-dev libffi-dev openssl-dev python3-dev make rsync yaml-dev curl

# Install Rust using rustup
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y \
    && source "$HOME/.cargo/env" \
    && ln -s $HOME/.cargo/bin/cargo /usr/local/bin/cargo \
    && ln -s $HOME/.cargo/bin/rustc /usr/local/bin/rustc

# Set working directory
WORKDIR /src

# Download ElasticHQ
RUN wget -q $(wget -qO- https://api.github.com/repos/ElasticHQ/elasticsearch-HQ/releases/latest | grep "zipball_url" | cut -d '"' -f 4) -O /tmp/elastichq.zip \
    && unzip /tmp/elastichq.zip -d /tmp \
    && rsync -a /tmp/*elasticsearch-HQ*/* /src --remove-source-files

# Install app dependencies and create supervisord dirs
RUN pip install pyyaml==5.3.1 \
    && pip install -U -r requirements.txt \
    && pip install gunicorn==19.7.1 \
    && mkdir -p /etc/supervisor/conf.d /var/log/supervisor /var/run/supervisor

# Copy configuration files
RUN cp /src/deployment/logging.conf /src/logging.conf && \
    cp /src/deployment/gunicorn.conf /src/gunicorn.conf && \
    cp /src/deployment/supervisord.conf /etc/supervisor/supervisord.conf && \
    cp /src/deployment/gunicorn.conf /etc/supervisor/conf.d/gunicorn.conf

# Fix permissions
RUN chgrp -R 0 /src /var/log/supervisor /var/run/supervisor && \
    chmod -R g=u  /src /var/log/supervisor /var/run/supervisor

RUN rm -rf /tmp/*elasticsearch-HQ* && \
    rm -rf /tmp/elastichq.zip

# Expose service port
EXPOSE 5000

# Start processes
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
