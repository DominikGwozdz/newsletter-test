FROM php:8.0.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y apt-utils && apt-get install -y git && apt-get install -y libzip-dev && apt-get install -y zip && apt-get install -y libicu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip intl opcache session

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www
