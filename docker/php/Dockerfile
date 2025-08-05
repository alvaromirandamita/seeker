FROM php:8.3-fpm-alpine

ARG user
ARG uid

RUN apk update && apk add \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    shadow \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www
USER $user
