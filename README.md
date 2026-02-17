# Warden docker image repository

This repository contain image build configurations used in Magenius fork of [Warden](https://github.com/magenius-team/warden).

## Available Service Images

| Service                 | Versions | Build |
|:------------------------|:---------|:------|
| Varnish                 | 6.0, 7.0, 7.3, 7.5, 7.6, 7.7, 8.0 | [![Varnish build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-varnish.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-varnish.yml) |
| Redis                   | 6.2, 7.2, 7.4 | [![Redis build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-redis.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-redis.yml) |
| Valkey                  | 8.0, 8.1 | [![Valkey build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-valkey.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-valkey.yml) |
| RabbitMQ                | 3.12, 3.13, 4.0, 4.1, 4.2 | [![RabbitMQ build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-rabbitmq.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-rabbitmq.yml) |
| PHP + XDebug3 + PHP-SPX | 7.4, 8.0, 8.1, 8.2, 8.3 | [![PHP-FPM build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml) |
| Node JS                 | 12, 18, 20, 22, 23, 24 | [![PHP-FPM build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml) |
| Elasticsearch           | 7.17, 8.13 | [![Elasticsearch build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticsearch.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticsearch.yml) |
| OpenSearch              | 1.1, 1.2, 1.3, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9, 2.11, 2.12, 2.13, 2.14, 2.15, 2.16, 2.17, 2.18, 2.19, 3.0, 3.1, 3.2, 3.3, 3.4 | [![OpenSearch build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-opensearch.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-opensearch.yml) |
| Nginx                   | 1.25, 1.26, 1.27, 1.28, 1.29 | [![Nginx build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-nginx.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-nginx.yml) |
| MySQL                   | 8.0, 8.1, 8.2, 8.3, 8.4 | [![MySQL build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mysql.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mysql.yml) |
| MariaDB                 | 10.5, 10.6, 10.11, 11.4, 11.5, 11.6 | [![MariaDB build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mariadb.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mariadb.yml) |
| Magepack                | 2.11 | [![Magepack build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-magepack.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-magepack.yml) |
| Traefik                 | 3.5, 3.6 | [![Traefik build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-traefik.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-traefik.yml) |
| Minio                   | latest | [![Minio build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-minio.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-minio.yml) |
| Elasticvue              | latest | [![Elasticvue build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticvue.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticvue.yml) |
| Buggregator             | latest | [![Buggregator build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-buggregator.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-buggregator.yml) |

## License

This work is licensed under the MIT license. See [LICENSE](https://github.com/magenius-team/warden-images/blob/main/LICENSE) file for details.

## Maintainers

This repository is maintained by the Magenius.Team.

- [Denis Kopylov](https://www.linkedin.com/in/dkopylov/)

Questions or suggestions: please use [GitHub Issues](https://github.com/magenius-team/warden-images/issues).
