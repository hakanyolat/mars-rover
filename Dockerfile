FROM php:8.0.0rc1-fpm

RUN apt-get update && apt-get install -y git

WORKDIR /var/www/html/

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY . .

RUN composer install
