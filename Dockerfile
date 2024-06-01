FROM composer as composer_build
WORKDIR /app
COPY . /app
RUN composer install --no-dev --optimize-autoloader

FROM php:8.3.7
WORKDIR /app
RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip unzip && \
    docker-php-ext-install pdo pdo_mysql mysqli && \
    docker-php-ext-enable pdo pdo_mysql mysqli && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY --from=composer_build /app/ /app/
RUN php artisan key:generate
EXPOSE 80
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
