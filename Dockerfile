FROM php:8.2-apache

# Install dependencies for MySQL
RUN apt-get update && \
    docker-php-ext-install mysqli pdo_mysql && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html
