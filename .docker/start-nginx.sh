#!/bin/sh

until nc -z mba-patters-php 9000; do
  sleep 1
done

exec nginx -g 'daemon off;'
