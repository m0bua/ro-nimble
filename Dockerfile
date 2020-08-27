FROM php:7.4-cli as builder
RUN apt-get update && \
    apt-get install wget zip unzip -y && \
    wget https://getcomposer.org/download/1.9.3/composer.phar --output-document=/bin/composer.phar && \
    chmod a+x /bin/composer.phar && \
    docker-php-ext-install sockets
ADD . /nimble
WORKDIR /nimble
RUN /bin/composer.phar install

FROM php:7.4-cli as prod
RUN docker-php-ext-install sockets && \
    printf "\n" | pecl install redis && \
    docker-php-ext-enable redis
COPY --from=builder /nimble/ /nimble/
WORKDIR /nimble
ENTRYPOINT ["php", "artisan", "consumer:start"]
