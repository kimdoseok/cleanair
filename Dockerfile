FROM php:8.2-apache

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
#RUN docker-php-ext-install gd && docker-php-ext-enable gd
#RUN apt install cups cups-client -y
#RUN apt install lprng -y
#RUN apt install lpr -y
#RUN apk update

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
#COPY cups/printers.conf /etc/cups/printers.conf
#COPY index.html /var/www/html/index.html
#COPY .htpasswd /var/www/html/.htpasswd
#COPY info.php /var/www/html/info.php
#COPY ca/ /var/www/html/ca
#COPY cae/ /var/www/html/cae
