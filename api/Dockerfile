FROM thecodingmachine/php:7.3-v2-apache

COPY --chown=docker:docker . .

ENV PHP_EXTENSIONS="gd zip"

RUN composer install
