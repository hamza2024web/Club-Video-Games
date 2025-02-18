FROM php:8.4-apache

# Install MySQL PDO extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite
