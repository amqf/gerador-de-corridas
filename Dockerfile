# Use the official PHP 8.3 image with FPM
FROM php:8.3-fpm

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install mbstring pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Caddy
RUN apt-get update && apt-get install -y debian-archive-keyring curl \
    && curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | apt-key add - \
    && curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | tee /etc/apt/sources.list.d/caddy-stable.list \
    && apt-get update && apt-get install caddy

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Set working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80

# Start PHP-FPM and Caddy
CMD ["sh", "-c", "php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile"]
