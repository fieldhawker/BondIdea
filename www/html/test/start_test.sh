#!/bin/sh

cd /var/www/html/

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always --verbose \
  models/
#  --testdox \

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always --verbose \
  core/
#  --testdox \

