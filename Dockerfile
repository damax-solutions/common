FROM php:7.1-cli

LABEL maintainer="dmitri@devmachine.net"

# zip
RUN apt-get update && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip \
    && rm -r /var/lib/apt/lists/*

# Composer
RUN cd /usr/local/bin \
    && php -r "readfile('https://getcomposer.org/installer');" | php \
    && mv composer.phar composer

ENV SYMFONY_PHPUNIT_VERSION=6.5
