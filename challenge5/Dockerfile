FROM php:8.5-apache

COPY /src/ /var/www/html

COPY /docker/security.conf /etc/apache2/conf-available/security.conf
COPY /docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY /docker/apache2.conf /etc/apache2/apache2.conf
COPY /docker/php.ini /usr/local/etc/php/php.ini

ENV TZ=Europe/Berlin
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN a2enmod headers && service apache2 restart
