FROM php:8.2-fpm

# 基本パッケージ
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip \
    libzip-dev libpq-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www
COPY . .

# パーミッション
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]