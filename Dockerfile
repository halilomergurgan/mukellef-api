FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    libonig-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

EXPOSE 9000

CMD ["php-fpm"]
