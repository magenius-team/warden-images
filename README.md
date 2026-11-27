# Warden docker image repository

This repository contain image build configurations used in Magenius fork of [Warden](https://github.com/magenius-team/warden).

## Available Service Images

| Service                 | Versions | Build |
|:------------------------|:---------|:------|
| Varnish                 | 6.0, 7.0, 7.3, 7.5, 7.6, 7.7, 8.0 | [![Varnish build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-varnish.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-varnish.yml) |
| Redis | 6.2, 7.2, 7.4, 8.0 | [![Redis build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-redis.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-redis.yml) ||
| Valkey                  | 8.0, 8.1 | [![Valkey build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-valkey.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-valkey.yml) |
| RabbitMQ | 3.12, 3.13, 4.0, 4.1, 4.2, 4.3 | [![RabbitMQ build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-rabbitmq.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-rabbitmq.yml) ||
| PHP + XDebug3 + PHP-SPX | 7.4, 8.0, 8.1, 8.2, 8.3 | [![PHP-FPM build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml) |
| Node JS                 | 12, 18, 20, 22, 23, 24 | [![PHP-FPM build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-general-php-fpm.yml) |
| Elasticsearch           | 7.17, 8.13 | [![Elasticsearch build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticsearch.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticsearch.yml) |
| OpenSearch | 1.1, 1.2, 1.3, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9, 2.11, 2.12, 2.13, 2.14, 2.15, 2.16, 2.17, 2.18, 2.19, 3.0, 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9 | [![OpenSearch build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-opensearch.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-opensearch.yml) ||||||
| Nginx | 1.25, 1.26, 1.27, 1.28, 1.29, 1.30 | [![Nginx build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-nginx.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-nginx.yml) ||
| MySQL                   | 8.0, 8.1, 8.2, 8.3, 8.4 | [![MySQL build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mysql.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mysql.yml) |
| MariaDB | 10.3, 10.3.39, 10.4, 10.4.34, 10.5, 10.6, 10.11, 11.4, 11.5, 11.6, 11.8, 12.3 | [![MariaDB build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mariadb.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-mariadb.yml) |||
| Magepack                | 2.11 | [![Magepack build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-magepack.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-magepack.yml) |
| Traefik                 | 3.5, 3.6 | [![Traefik build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-traefik.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-traefik.yml) |
| Minio                   | latest | [![Minio build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-minio.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-minio.yml) |
| Elasticvue              | latest | [![Elasticvue build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticvue.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-elasticvue.yml) |
| Buggregator | 1.13.7, 1.16.0, 2.1.0, 2.2.0, 2.2.1, 2.3.0, 2.3.1, 2.4.0, 2.4.1, 2.7.0, 2.9.0, 2.13.0 | [![Buggregator build](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-buggregator.yml/badge.svg?branch=main)](https://github.com/magenius-team/warden-images/actions/workflows/docker-image-buggregator.yml) |||||||||||||

## Allure candidate (unbuilt and unpublished)

`images/allure` defines an Allure 2.44.0 candidate for `linux/amd64` and
`linux/arm64`. It builds the runtime from `eclipse-temurin:11-jre-noble` for
the target platform, rather than rebasing the upstream service image whose
2.44.0 arm64 manifest contains x86-64 executables.
The API and scripts come from
[fescobar/allure-docker-service](https://github.com/fescobar/allure-docker-service/tree/790d13f5e5283bcb839d68fe238546991af480db),
pinned at `790d13f5e5283bcb839d68fe238546991af480db` through the required
`allure-source` named build context.

Local build recipe (run from this repository; choose the host's native
platform):

```sh
docker buildx build \
  --platform linux/arm64 \
  --build-context allure-source=https://github.com/fescobar/allure-docker-service.git#790d13f5e5283bcb839d68fe238546991af480db \
  --build-arg ALLURE_RELEASE=2.44.0 \
  --build-arg BUILD_VERSION=2.44.0 \
  --build-arg BUILD_REF=790d13f5e5283bcb839d68fe238546991af480db \
  --tag allure:2.44.0-candidate --load images/allure
```

The workflow defines `ghcr.io/${{ github.repository_owner }}/allure:2.44.0`
with publication restricted to `main` outside `ACT`. Its definition is not
evidence of a successful build or publication. The initial push triggers the
multi-platform build and publication; their results remain unverified.
Final ELF architecture checks for Bash/Java/Python and native API/history
acceptance have not been performed for this candidate.
The pinned source is not a fully reproducible build: the base tag, package
repositories, and upstream `get-pip.py` download remain mutable.

The upstream runtime contract is retained: UID/GID 1000, user `allure`,
API port 5050, and the existing healthcheck and startup scripts.
Report history requires persistent report storage (for example,
`/app/default-reports`, or `/app/projects` for multiple projects), not only
`/app/allure-results`; configure upstream history retention as needed.

## License

This work is licensed under the MIT license. See [LICENSE](https://github.com/magenius-team/warden-images/blob/main/LICENSE) file for details.

Exception: the Allure Dockerfile is adapted from Frank Escobar's
`allure-docker-service` runtime definition at the commit above, under
Apache-2.0, not this repository's MIT license. Its modification notice and
upstream author attribution are retained in `images/allure/Dockerfile`.
The full unchanged upstream license is in [images/allure/LICENSE](images/allure/LICENSE)
and is copied into the image at
`/usr/share/licenses/allure-docker-service/LICENSE`. Upstream API and script
notices are retained by copying those source directories unchanged.

## Maintainers

This repository is maintained by the Magenius.Team.

- [Denis Kopylov](https://www.linkedin.com/in/dkopylov/)

Questions or suggestions: please use [GitHub Issues](https://github.com/magenius-team/warden-images/issues).
