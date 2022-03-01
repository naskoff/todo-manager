FROM php:7.4-cli

RUN apt-get update -y && apt-get install -y libpq-dev libmcrypt-dev npm && docker-php-ext-install pgsql pdo_pgsql

RUN npm i -g yarn

WORKDIR /var/www/html

COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-interaction --prefer-dist && yarn && yarn encore prod

EXPOSE 8080

CMD php -S 0.0.0.0:8080 -t public
