FROM php:5.6-zts
RUN apt-get update && apt-get install git -y \
    && pecl install pthreads-2.0.10 \
    && docker-php-ext-enable pthreads \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer
COPY ./solution/ /usr/src/dragonsofmugloar
RUN composer install --prefer-dist -o --no-dev --no-plugins --no-scripts -d /usr/src/dragonsofmugloar
WORKDIR /usr/src/dragonsofmugloar
CMD [ "php", "./run.php" ]