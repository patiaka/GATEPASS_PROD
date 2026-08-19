# syntax=docker/dockerfile:1

# ============================================================================
#  GatePass — production image for Dokploy (Laravel 12 + Livewire + SQL Server)
#  Serves HTTP on :80 with FrankenPHP (put behind Dokploy's Traefik proxy).
# ============================================================================

# ---------------------------------------------------------------------------
# 1) Front-end assets (Vite + Tailwind v4)
# ---------------------------------------------------------------------------
FROM node:20-bookworm-slim AS assets
WORKDIR /app
# Puppeteer ships with spatie/browsershot but is NOT needed to build assets.
ENV PUPPETEER_SKIP_DOWNLOAD=true \
    PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
COPY package.json package-lock.json ./
RUN npm ci
# Tailwind v4 scans the templates for class names → copy the whole project
# (node_modules/vendor/etc. are excluded by .dockerignore).
COPY . .
RUN npm run build

# ---------------------------------------------------------------------------
# 2) PHP dependencies (Composer)
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
      --no-dev --no-scripts --no-autoloader \
      --prefer-dist --no-interaction --no-progress --ignore-platform-reqs

# ---------------------------------------------------------------------------
# 3) Runtime (FrankenPHP + Microsoft ODBC / pdo_sqlsrv)
# ---------------------------------------------------------------------------
FROM dunglas/frankenphp:1-php8.3-bookworm AS runtime

# install-php-extensions (bundled) also installs the MS ODBC driver for sqlsrv.
ENV ACCEPT_EULA=Y
RUN install-php-extensions \
      pdo_sqlsrv \
      sqlsrv \
      intl \
      zip \
      gd \
      bcmath \
      opcache \
      pcntl

WORKDIR /app

# Composer binary (needed to dump the optimized autoloader).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Vendor, then application source, then freshly built assets.
COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build

# Optimized autoloader + package discovery, writable runtime dirs, prod php.ini.
RUN composer dump-autoload --optimize --no-dev --no-interaction \
 && php artisan package:discover --ansi \
 && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
             storage/app/public storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# OPcache tuning for production.
RUN { \
      echo 'opcache.enable=1'; \
      echo 'opcache.enable_cli=0'; \
      echo 'opcache.memory_consumption=192'; \
      echo 'opcache.max_accelerated_files=20000'; \
      echo 'opcache.validate_timestamps=0'; \
      echo 'opcache.interned_strings_buffer=16'; \
      echo 'upload_max_filesize=25M'; \
      echo 'post_max_size=28M'; \
    } > "$PHP_INI_DIR/conf.d/zz-gatepass.ini"

COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

ENV APP_ENV=production \
    APP_DEBUG=false \
    OCTANE_SERVER=frankenphp

EXPOSE 80
ENTRYPOINT ["app-entrypoint"]
