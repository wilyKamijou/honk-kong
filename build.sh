#!/bin/bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan optimize:clear
php artisan storage:link
