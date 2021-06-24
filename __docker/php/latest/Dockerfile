FROM php:7.4-fpm

RUN pecl install xdebug-2.8.0 && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


RUN mkdir -p /usr/share/man/man1
RUN mkdir -p /usr/share/man/man7

RUN apt-get update \
    && apt-get install -y libzip-dev zip && docker-php-ext-install zip \
    && apt-get install -y libpq-dev wget unzip re2c libgearman-dev mc git locales mariadb-client postgresql-client \
    && docker-php-ext-install pdo_pgsql pdo_mysql bcmath mysqli \
    && apt-get install sudo\
    && apt-get install sshpass

# install docker
RUN curl -sSL https://get.docker.com/ | sh

# install composer globally
RUN wget -O /tmp/composer-setup.php https://getcomposer.org/installer
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm /tmp/composer-setup.php

# for Mac
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl && docker-php-ext-install intl
# end for Mac

# install php pcntl
RUN docker-php-ext-install pcntl

# SSH2
RUN apt-get update && apt-get install -y libssh2-1-dev
RUN git clone https://github.com/php/pecl-networking-ssh2.git /usr/src/php/ext/ssh2 && docker-php-ext-install ssh2

# add git completion
RUN wget -O /etc/bash_completion.d/git-completion.bash https://raw.githubusercontent.com/git/git/master/contrib/completion/git-completion.bash
RUN echo "source /etc/bash_completion.d/git-completion.bash" >> /root/.bashrc

# setting right locale
RUN sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    echo 'LANG="en_US.UTF-8"'>/etc/default/locale && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=en_US.UTF-8
ENV LANG en_US.UTF-8

RUN composer global require "fxp/composer-asset-plugin:~1.4"

RUN apt-get autoclean

RUN apt-get update \
&& apt-get install -y vim \
&& apt-get install -y nano

RUN docker-php-ext-install \
    bcmath \
    zip

# imagick
RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick

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
#

WORKDIR /var/www
