#!/bin/bash
composer update --lock --no-interaction
composer install --optimize-autoloader --no-scripts --no-interaction
