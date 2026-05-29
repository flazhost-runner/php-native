#!/bin/sh
set -e

# Sesuaikan port Apache dengan env PORT (default 80).
# Runner FlazHost / CapRover inject PORT supaya bisa multi-tenant share host.
PORT="${PORT:-80}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

exec "$@"
