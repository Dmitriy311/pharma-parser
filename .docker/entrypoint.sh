#!/bin/sh

echo '['$(date '+%d/%m/%Y %H:%M:%S')'] launching php-fpm83...'
php-fpm83
while ! pidof php-fpm83 >> /dev/null ;
  do sleep 3
     echo '...'
  done
echo 'php-fpm is up!'

echo '['$(date '+%d/%m/%Y %H:%M:%S')'] launching nginx...'
nginx
while ! pidof nginx >> /dev/null ;
  do sleep 3
     echo '...'
  done
echo 'nginx is up!'

echo "192.168.0.101    localhost" >> /etc/hosts

sleep 15
php /app/bin/console make:migration --no-interaction
php /app/bin/console doctrine:migrations:migrate --no-interaction

while true; do sleep 1000; done