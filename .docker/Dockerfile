FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache \
    openssl \
    bash \
    postgresql-client \
    postgresql-dev \
    autoconf \
    nginx \
    gcc \
    g++ \
    make \
    $PHPIZE_DEPS \
    linux-headers

RUN docker-php-ext-install pdo pdo_pgsql bcmath

RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . .


COPY ./start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 9000

ENTRYPOINT [ "/start.sh" ]
