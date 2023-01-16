FROM bitnami/php-fpm:8.0.15-debian-10-r10
RUN apt-get update
RUN apt-get install -y sudo dialog apt-utils gnupg2 ca-certificates apt-transport-https software-properties-common
RUN wget -qO - https://packages.sury.org/php/apt.gpg | sudo apt-key add -
RUN echo "deb https://packages.sury.org/php/ buster main" | sudo tee /etc/apt/sources.list.d/php.list
RUN apt-get update
RUN apt-get install -y git php8.0-mysql libmcrypt-dev libfreetype6-dev libjpeg62-turbo-dev

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN mkdir -p /var/www/php-app
WORKDIR /var/www/php-app