#!/bin/bash

cd /var/www/zcpe/
chmod -Rf 777 .
php app/console assets:install --symlink web