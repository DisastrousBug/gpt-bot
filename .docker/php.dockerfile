FROM php:8.4-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

RUN apk add --no-cache postgresql-dev

RUN addgroup -g ${GID} --system docker
RUN adduser -G docker --system -D -s /bin/sh -u ${UID} dockerino


RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 775 /var/www/html
#RUN sed -i "s/user = www-data/user = docker/g" /usr/local/etc/php-fpm.d/www.conf
#RUN sed -i "s/group = www-data/group = docker/g" /usr/local/etc/php-fpm.d/www.conf
#RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

#RUN docker-php-ext-install pgsql pdo pdo_pgsql

RUN docker-php-ext-install pgsql pdo_pgsql

# ─── Redis extension ─────────────────────────────────────────────────────
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del .build-deps

USER dockerino

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
