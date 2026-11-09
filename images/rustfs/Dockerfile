ARG RUSTFS_VERSION=1.0.4
ARG RCLONE_VERSION=1.76.0

FROM rclone/rclone:${RCLONE_VERSION} AS rclone

FROM rustfs/rustfs:${RUSTFS_VERSION}

COPY --from=rclone /usr/local/bin/rclone /usr/local/bin/rclone
