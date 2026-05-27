#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

if [ ! -f .env ]; then
  cp .env.local.example .env
  echo "Создан .env из .env.local.example — проверьте DB_USER и DB_PASSWORD."
fi

if [ ! -d vendor ]; then
  composer install --no-interaction
fi

php database/seed.php
echo ""
echo "Готово. Запуск сервера:"
echo "  php -S localhost:8080 -t public"
echo "Откройте: http://localhost:8080"
