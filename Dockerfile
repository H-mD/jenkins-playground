FROM composer:2.3.8 as composer_build
WORKDIR /app
COPY . /app
COPY ../.env /app/.env
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist

FROM php:8.3.7
COPY --from=composer_build /app/ /app/
WORKDIR /app
RUN php artisan key:generate
CMD php artisan serve --host=0.0.0.0 --port 80
EXPOSE 80