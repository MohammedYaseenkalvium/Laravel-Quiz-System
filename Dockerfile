FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN mkdir -p bootstrap/cache storage && chmod -R 777 bootstrap/cache storage

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}