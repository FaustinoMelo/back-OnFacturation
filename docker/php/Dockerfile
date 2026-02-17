FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# PHP config
COPY php.ini /usr/local/etc/php/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]
