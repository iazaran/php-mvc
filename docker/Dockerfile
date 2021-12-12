FROM php:8.0-fpm
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    memcached libmemcached-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    jpegoptim optipng pngquant gifsicle \
    cron

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install mysqli pdo pdo_mysql gd
RUN pecl install -o -f memcached \
    &&  docker-php-ext-enable memcached

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY ./ /var/www
COPY --chown=www:www . /var/www

COPY crontab /etc/cron.d/crontab
RUN chmod +x /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab

EXPOSE 9000
