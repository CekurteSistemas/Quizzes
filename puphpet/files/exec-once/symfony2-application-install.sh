#!/bin/bash

cd /var/www/zcpe/
chmod -Rf 777 .
composer install --no-interaction
bower install --allow-root
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load --no-interaction
php app/console assets:install --symlink web