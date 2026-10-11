FROM php:8.3-cli-alpine

# Install system dependencies and SQLite/GD extensions
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite-dev \
    nodejs \
    npm

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_sqlite zip bcmath

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies & build assets
RUN npm install && npm run build

# Prepare SQLite DB & permissions
RUN mkdir -p /app/database /app/storage /app/bootstrap/cache \
    && touch /app/database/database.sqlite \
    && chmod -R 777 /app/storage /app/bootstrap/cache /app/database \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/database

EXPOSE 8000

CMD ["sh", "-c", "chmod -R 777 /app/database /app/storage /app/bootstrap/cache && php artisan migrate --force && php artisan db:seed --force && chmod -R 777 /app/database /app/storage /app/bootstrap/cache && php artisan serve --host=0.0.0.0 --port=8000"]
