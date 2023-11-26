FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip
RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer

WORKDIR /server

COPY . .

RUN composer update
RUN php artisan key:generate
RUN php artisan jwt:secret
RUN php artisan jwt:generate-certs

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
