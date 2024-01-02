FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils build-essential curl zip zlib1g-dev libssl-dev unzip \
    memcached libmemcached-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    jpegoptim optipng pngquant gifsicle \
    cron \
    protobuf-compiler-grpc libprotobuf-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install mysqli pdo pdo_mysql gd sockets
RUN pecl install -o -f memcached \
    && docker-php-ext-enable memcached
RUN pecl install grpc \
    && docker-php-ext-enable grpc
RUN pecl install protobuf \
    && docker-php-ext-enable protobuf
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY ./docker/crontab /etc/cron.d/crontab
RUN chmod +x /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab

COPY ./ /var/www
COPY --chown=www:www . /var/www

WORKDIR /var/www

RUN composer install

EXPOSE 9000
