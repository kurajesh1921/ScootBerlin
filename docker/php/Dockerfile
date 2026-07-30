FROM php:8.4-fpm

# Install system dependencies required by Laravel and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip

    # Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis
    
# Copy Composer from the official Composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy custom PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Create a non-root user for the application
RUN groupadd -g 1000 laravel \
    && useradd -u 1000 -ms /bin/bash -g laravel laravel

# Switch to the non-root user
USER laravel