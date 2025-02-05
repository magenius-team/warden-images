ARG MYSQL_VERSION
FROM public.ecr.aws/docker/library/mysql:${MYSQL_VERSION}

# MySQL 8.0 require skip-bin-log to avoid workarounds:
# See: https://github.com/davidalger/warden/pull/403
COPY etc/mysql/conf.d/skip-bin-log.cnf /etc/mysql/conf.d/
