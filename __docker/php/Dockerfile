FROM php:7.4.19-fpm
#USER www-data

RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis
RUN pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/selection-api
