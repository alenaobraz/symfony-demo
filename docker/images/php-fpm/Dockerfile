FROM php:8.4-fpm-alpine3.21

# Установка Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --version 2.8.5 \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Установка imagick исходников, чтобы можно было его
# установить через docker-php-ext-install
RUN mkdir -p /usr/src/php/ext/imagick \
    && curl -fsSL https://pecl.php.net/get/imagick | tar xvz -C "/usr/src/php/ext/imagick" --strip 1

RUN set -xe \
    && apk update \
    && apk add --no-cache \
        icu-dev \
        rabbitmq-c \
        rabbitmq-c-dev \
        libssh-dev \
        imagemagick \
        imagemagick-dev \
        # Для imagick, pdf
        ghostscript \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        bcmath \
        intl \
        opcache \
        mysqli \
        pdo_mysql \
        pdo \
        imagick

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS autoconf \
    && pecl install amqp-1.11.0beta \
    && docker-php-ext-enable amqp \
    && apk del .build-deps
