FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git unzip zip \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8080
CMD php bin/console doctrine:migrations:migrate --no-interaction && \
    php -S 0.0.0.0:8080 -t public
