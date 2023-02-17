FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    librabbitmq-dev

RUN docker-php-ext-install intl pdo_mysql gd zip bcmath && \
    pecl install amqp && docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN composer install

RUN chown -R www-data:www-data var

RUN a2enmod rewrite