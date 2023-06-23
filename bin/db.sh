#!/bin/bash

: '
This script recreates database from scratch
'

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
