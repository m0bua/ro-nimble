FROM php:7.4.19-fpm
#USER www-data

RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis && docker-php-ext-install sockets
RUN pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip git htop && docker-php-ext-install zip pdo pdo_pgsql mysqli pdo_mysql pcntl && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN userdel -f www-data \
    && if getent group www-data ; then groupdel www-data; fi \
    && groupadd -g ${GROUP_ID} www-data \
    && useradd -l -u ${USER_ID} -g www-data www-data && echo "www-data:www-data" | chpasswd && adduser www-data sudo \
    && install -d -m 0755 -o www-data -g www-data /home/www-data

RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

RUN chown --changes --silent --no-dereference --recursive ${USER_ID}:${GROUP_ID}  \
    /home/www-data  \
    /var/run/php-fpm \
    /var/lib/php/sessions \
    /var/log \
    /var/www || true

#RUN usermod --shell /bin/bash www-data
USER www-data

WORKDIR /var/www/selection-api
