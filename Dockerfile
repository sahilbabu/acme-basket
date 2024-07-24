# Use the official PHP-FPM image
FROM php:8.2-fpm

# Install necessary extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files to the container
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html
