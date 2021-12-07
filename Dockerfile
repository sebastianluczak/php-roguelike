FROM php:7.4-cli
COPY . /app
WORKDIR /app
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN apt update
RUN apt install -y wget netcat zip
RUN wget -O composer-setup.php https://getcomposer.org/installer
RUN php composer-setup.php --filename=composer.phar
RUN php composer.phar install
RUN chmod +x /app/bin/console
RUN mkdir -p /app/var/log
RUN touch /app/var/log/dev.log
RUN mkdir -p /app/var/cache
RUN chmod +x /app/bin/entrypoint.sh
CMD ['/app/bin/entrypoint.sh']
