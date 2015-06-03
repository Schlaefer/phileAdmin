 #!/usr/bin/env bash

cd "$(dirname "$0")";

### install composer packages
rm -rf vendor;
composer install;

### remove php native packages
rm -rf vendor/phile-cms
rm -rf vendor/michelf
rm -rf vendor/phpfastcache
rm -rf vendor/twig/twig

### recreate dummy include for removed phpFastCache composer inclusion
mkdir -p vendor/phpfastcache/phpfastcache/
touch vendor/phpfastcache/phpfastcache/phpfastcache.php

### remove other fat
rm -rf vendor/twitter/bootstrap/docs

### create archive
rm *.zip
composer archive --format=zip
mv *.zip phileAdmin.zip
