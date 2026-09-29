#!/usr/bin/env bash
php artisan clear-compiled
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan debugbar:clear
composer dump-autoload
