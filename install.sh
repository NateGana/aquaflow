#!/usr/bin/env bash
set -euo pipefail

TARGET="${1:-aquaflow}"
KIT="$(cd "$(dirname "$0")" && pwd)"
DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-aquaflow}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"

for tool in php composer node npm; do
    command -v "$tool" >/dev/null 2>&1 || { echo "Missing required tool: $tool" >&2; exit 1; }
done

set_env() {
    local key="$1" value="$2"
    if grep -qE "^#?[[:space:]]*${key}=" .env; then
        sed -i.bak -E "s|^#?[[:space:]]*${key}=.*|${key}=${value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

composer create-project laravel/laravel "$TARGET" --no-scripts --no-interaction
cd "$TARGET"

rm -f database/migrations/*create_users_table.php tests/Feature/ExampleTest.php tests/Unit/ExampleTest.php resources/views/welcome.blade.php
cp -R "$KIT/app" "$KIT/bootstrap" "$KIT/database" "$KIT/resources" "$KIT/routes" "$KIT/tests" "$KIT/tools" .

[ -f .env ] || cp .env.example .env
composer dump-autoload --no-interaction
php artisan key:generate --force

set_env APP_NAME AquaFlow
set_env DB_CONNECTION "$DB_CONNECTION"
if [ "$DB_CONNECTION" = "mysql" ]; then
    set_env DB_HOST "$DB_HOST"
    set_env DB_PORT "$DB_PORT"
    set_env DB_DATABASE "$DB_DATABASE"
    set_env DB_USERNAME "$DB_USERNAME"
    set_env DB_PASSWORD "$DB_PASSWORD"
    if command -v mysql >/dev/null 2>&1; then
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" ${DB_PASSWORD:+-p"$DB_PASSWORD"} -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" \
            || echo "Could not create the database automatically. Create it manually, then run: php artisan migrate:fresh --seed"
    fi
else
    touch database/database.sqlite
fi
rm -f .env.bak

php tools/strip_comments.php
php artisan storage:link --force
php artisan migrate:fresh --seed --force

npm install
npm install alpinejs chart.js
npm run build

echo
echo "AquaFlow is ready in: $(pwd)"
echo "Start it with:  php artisan serve   (then open http://127.0.0.1:8000)"
echo "Admin login:    admin@aquaflow.test / password"
echo "Member login:   swimmer@aquaflow.test / password"
