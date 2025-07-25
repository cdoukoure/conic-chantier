# 📌 Base image PHP avec FPM
FROM php:8.0.30-fpm

# 👨‍🔧 Mainteneur
LABEL maintainer="Jean Charles Doukouré <c.doukoure@outlook.fr>"

# 🧰 Install system packages and PHP extensions
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        git=* \
        curl=* \
        unzip=* \
        libpng-dev=* \
        libonig-dev=* \
        libxml2-dev=* \
        libzip-dev=* \
        zip=* && \
    docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        zip \
        gd \
        xml

# 🧹 Clean
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 📦 Installer Composer (v2)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🛠️ Set working directory
WORKDIR /var/www/html

# ⚙️ Droits d'accès (à adapter selon ton setup)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
    # Ces répertoires suivants sont créé après l'installation manuelle de Laravel
    # && chmod -R 775 /var/www/html/storage \
    # && chmod -R 775 /var/www/html/bootstrap/cache


# ✅ Health check (optionnel mais pro)
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s CMD php-fpm -t || exit 1
