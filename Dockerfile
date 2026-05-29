# PHP Native (Apache) — siap deploy aplikasi PHP klasik.
# Pakai PHP-Apache official image + ekstensi umum (mysqli, gd, zip, mbstring, intl).
# Listen di env PORT (di-inject runner / CapRover). Default 80.
FROM php:8.2-apache

# Ekstensi PHP yang umum dibutuhkan:
# - mysqli, pdo_mysql : koneksi MySQL
# - gd                : pemrosesan gambar (upload, resize)
# - zip               : import/export
# - mbstring, intl    : i18n
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql gd zip mbstring intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite + izinkan .htaccess override (umum untuk PHP framework).
RUN a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Naikkan batas upload (override via env atau replace file ini di repo Anda).
RUN { \
        echo "upload_max_filesize=64M"; \
        echo "post_max_size=80M"; \
        echo "memory_limit=256M"; \
        echo "max_execution_time=120"; \
    } > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

# Salin source aplikasi. Pakai .dockerignore di repo untuk exclude .env, node_modules, dll.
COPY . /var/www/html/

# Pastikan folder yang perlu ditulis web server bisa diakses.
# Sesuaikan path di bawah dengan struktur proyek Anda (default: /var/www/html/uploads).
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} ;

# Runner flazhost (dan CapRover) inject env PORT — Apache harus dengar di sana.
ENV PORT=80
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh 2>/dev/null || true

EXPOSE 80
# Kalau ada docker-entrypoint.sh, dipakai. Kalau tidak, langsung apache2-foreground.
CMD ["sh", "-c", "if [ -x /usr/local/bin/docker-entrypoint.sh ]; then docker-entrypoint.sh apache2-foreground; else apache2-foreground; fi"]
