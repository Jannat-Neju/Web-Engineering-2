FROM php:8.2-apache

# Install PostgreSQL (if you REALLY need it)
RUN apt-get update && apt-get install -y libpq-dev

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo_mysql

# Install PostgreSQL extensions (optional)
RUN docker-php-ext-install pdo_pgsql pgsql

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/