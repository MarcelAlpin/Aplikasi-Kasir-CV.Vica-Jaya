FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsodium-dev \
    libpq-dev \
    default-mysql-client \
    default-libmysqlclient-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip sodium

# Get Composer
COPY --from composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader

# Generate application key (if not already set)
RUN php artisan key:generate --force

# Cache configuration for better performance
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set Railway-specific environment variable for PORT
ENV PORT=8000

# Expose port based on Railway's PORT environment variable
EXPOSE ${PORT}

# Start the Laravel server using PORT environment variable that Railway sets
CMD php artisan serve --host=0.0.0.0 --port=$PORT