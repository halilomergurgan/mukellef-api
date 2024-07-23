#!/bin/bash

set -e

cd "$(dirname "$0")"

if [ ! -f .env ]; then
  echo "Creating .env file from .env.example"
  cp .env.example .env
fi

echo "Building and starting Docker containers..."
docker-compose down
docker-compose up -d --build

echo "Waiting for MySQL container to be ready..."
while ! docker exec laravel_mysql mysqladmin --user=laravel --password=secret --host "127.0.0.1" ping --silent &> /dev/null ; do
  echo "MySQL is unavailable - waiting..."
  sleep 3
done

echo "Clearing Laravel cache..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

echo "Running Laravel migrations..."
docker-compose exec app php artisan migrate --force

echo "Setup complete. You can now access your project at http://mukellef-api.test"

