# Minimalist Dockerfile for Laravel + PHP-FPM
FROM php:8.2-fpm

# Install minimum required packages and extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libpng-dev \
       libjpeg-dev \
       libfreetype6-dev \
       zip \
       unzip \
       libonig-dev \
       libxml2-dev \
       libzip-dev \
    && docker-php-ext-install \
       pdo_mysql \
       mbstring \
       zip \
       exif \
       pcntl \
       bcmath \
       gd \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer from official image
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY app/. ./

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
