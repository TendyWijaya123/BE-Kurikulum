# Gunakan FrankenPHP sebagai base image
FROM dunglas/frankenphp

# Install PHP extensions yang diperlukan
RUN install-php-extensions \
  pcntl gd mbstring pdo pdo_mysql xml bcmath zip sockets

# Install dependencies tambahan
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  curl \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  supervisor procps \
  && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app/backend

# Copy Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua file project
COPY . .

# Install Laravel Octane
RUN composer require laravel/octane --no-interaction

# Install Octane dengan FrankenPHP server
RUN php artisan octane:install --server=frankenphp --no-interaction

RUN composer install --no-dev --optimize-autoloader

RUN php artisan key:generate

RUN php artisan storage:link

RUN chmod -R 775 storage bootstrap/cache && \
  chown -R www-data:www-data storage bootstrap/cache && \
  php artisan storage:link

RUN chmod +x artisan

# Copy konfigurasi Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port untuk Laravel Octane
EXPOSE 8000

# Jalankan Supervisor (agar Octane & Queue berjalan bersamaan)
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
