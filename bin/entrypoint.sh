#!/bin/bash
set -e
sleep 5
mkdir -p /app/db/data
php /app/bin/console d:d:c --if-not-exists &&
php /app/bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
exec "$@"