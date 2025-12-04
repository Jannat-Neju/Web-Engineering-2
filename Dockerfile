# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install dependencies for MySQL
RUN apt-get update && \
    docker-php-ext-install mysqli pdo_mysql && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Expose port 80 for HTTP
EXPOSE 80

# Copy project files into Apache's web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Optional: set permissions (if needed)
# RUN chown -R www-data:www-data /var/www/html
