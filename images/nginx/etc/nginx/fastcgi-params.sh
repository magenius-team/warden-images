#!/bin/bash

IFS=',' read -ra PARAMS <<< "$FASTCGI_PARAMS"
for i in "${PARAMS[@]}"; do
    KEY="${i%=*}"
    VALUE="${i#*=}"
    # Add the fastcgi_param line to the fastcgi_params file
    echo "fastcgi_param  $KEY  $VALUE;" >> /etc/nginx/fastcgi_params
done
