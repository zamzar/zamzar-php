FROM php:7.2-cli

# Install PHP extensions and composer
RUN apt-get update && \
    apt-get install -y libzip-dev zlib1g-dev zip && \
    docker-php-ext-install zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
RUN mkdir -p /usr/src/zamzar-php
COPY . /usr/src/zamzar-php
WORKDIR /usr/src/zamzar-php