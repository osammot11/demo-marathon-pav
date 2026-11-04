#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

LOCK_STATE="storage/app/.demo-composer-lock.sha256"
LOCK_HASH="$(php -r 'echo hash_file("sha256", "composer.lock");')"
PREVIOUS_HASH="$(cat "$LOCK_STATE" 2>/dev/null || true)"

if [[ ! -f vendor/autoload.php || "$LOCK_HASH" != "$PREVIOUS_HASH" ]]; then
    composer install --no-dev --optimize-autoloader --no-interaction
    printf '%s\n' "$LOCK_HASH" > "$LOCK_STATE"
fi

php artisan migrate --force
php artisan route:clear
php artisan config:clear
php artisan view:clear

if [[ "$(id -u)" -eq 0 ]]; then
    systemctl reload php8.4-fpm
else
    sudo -n systemctl reload php8.4-fpm
fi

echo "Demo deploy complete."
