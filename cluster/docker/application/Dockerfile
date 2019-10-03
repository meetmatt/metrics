FROM php:7.3.1-cli

RUN docker-php-ext-install pdo_mysql
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis
RUN docker-php-ext-install sockets