FROM php:8.4-fpm-alpine

# php-fpm, а затем dev-заголовки Postgres
RUN apk add --no-cache postgresql-dev

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Сборка расширений
RUN docker-php-ext-install pgsql pdo_pgsql

# ─── Redis extension ─────────────────────────────────────────────────────
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del .build-deps

USER root
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
