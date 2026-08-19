#!/bin/sh
set -e

cd /app

# ---------------------------------------------------------------------------
# Writable runtime dirs (a fresh persistent volume may be mounted at /app/storage)
# ---------------------------------------------------------------------------
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
         storage/app/public storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# ---------------------------------------------------------------------------
# APP_KEY — set a persistent one in Dokploy. Fallback avoids a boot crash only.
# ---------------------------------------------------------------------------
if [ -z "$APP_KEY" ]; then
  echo "WARNING: APP_KEY is not set — generating an EPHEMERAL key."
  echo "         Set a fixed APP_KEY in Dokploy, otherwise sessions/encryption"
  echo "         break on every restart. Generate one with: php artisan key:generate --show"
  export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

# Public storage symlink (uploaded documents, etc.)
php artisan storage:link --force >/dev/null 2>&1 || true

# config:cache is safe for every role (writes to per-container bootstrap/cache).
php artisan optimize:clear >/dev/null 2>&1 || true
php artisan config:cache

# ---------------------------------------------------------------------------
# Decide role: web server (default) vs. a passed command (scheduler/worker/…).
# ---------------------------------------------------------------------------
if [ "$#" -eq 0 ] || [ "$1" = "frankenphp" ]; then
  # Web-only warmups (view cache lives in the shared storage volume, so only
  # the web role writes it) and one-shot migrations.
  php artisan route:cache || echo "route:cache skipped"
  php artisan view:cache
  php artisan event:cache || true

  if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations…"
    php artisan migrate --force
  fi

  # Seed initial data (departments + an admin account) on a FRESH/EMPTY database.
  # Set RUN_SEED=true ONCE, then back to false. Do NOT run on a populated DB.
  if [ "$RUN_SEED" = "true" ]; then
    echo "Seeding database…"
    php artisan db:seed --force
  fi

  exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
fi

# Scheduler / queue worker / arbitrary command.
exec "$@"
