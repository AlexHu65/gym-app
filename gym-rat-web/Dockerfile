FROM node:24-bookworm-slim AS node
FROM composer:2 AS composer
FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libicu-dev libonig-dev libpq-dev libxml2-dev libzip-dev \
    && docker-php-ext-install intl mbstring pdo_pgsql dom xml xmlwriter zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules/npm /usr/local/lib/node_modules/npm
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY install-dependencies.sh /usr/local/bin/install-project-dependencies

WORKDIR /workspace
CMD ["sh", "/usr/local/bin/install-project-dependencies"]
