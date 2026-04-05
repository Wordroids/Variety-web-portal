# --- Stage 1: Build Assets (Frontend) ---
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Stage 2: Production Image (PHP) ---
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# 1. Install persistent runtime dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    icu-libs \
    libpng \
    libjpeg-turbo \
    libxml2 \
    freetype \
    libzip \
    libpq \
    oniguruma \
    supervisor

# 2. Install build-only dependencies using --virtual
RUN apk add --no-cache --virtual .build-deps \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    postgresql-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        bcmath \
        intl \
        mbstring \
        exif \
        pcntl \
        gd \
        zip \
        dom \
        xml \
        simplexml \
    && apk del .build-deps

# 3. Copy Composer from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Copy application code
COPY . /var/www/html

# 5. Copy built assets from the 'assets' stage
COPY --from=assets /app/public/build /var/www/html/public/build

# 6. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Start PHP-FPM
CMD ["php-fpm"]
