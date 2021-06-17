FROM php:7.4.19-fpm
#USER www-data

RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis && docker-php-ext-install sockets
RUN pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip git && docker-php-ext-install zip && docker-php-ext-install pdo pdo_pgsql mysqli pdo_mysql && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/selection-api
