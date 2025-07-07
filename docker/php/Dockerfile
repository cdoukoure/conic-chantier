# 📌 Base image PHP avec FPM
FROM php:8.2-fpm

# 👨‍🔧 Mainteneur
LABEL maintainer="Jean Charles Doukouré <c.doukoure@outlook.fr>"

# 🧰 Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        zip \
        gd \
        xml

# 📦 Installer Composer (v2)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🛠️ Set working directory
WORKDIR /var/www/html

# 🧹 Clean
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ⚙️ Droits d'accès (à adapter selon ton setup)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ✅ Health check (optionnel mais pro)
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s CMD php-fpm -t || exit 1
