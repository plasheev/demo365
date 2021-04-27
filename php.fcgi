#!/bin/bash

PHP_INI_SCAN_DIR=/home/plasheev/.sh.phpmanager/php80.d
export PHP_INI_SCAN_DIR

DEFAULTPHPINI=/home/plasheev/demo365.plasheev.com/php80-fcgi.ini
exec /opt/cpanel/ea-php80/root/usr/bin/php-cgi -c ${DEFAULTPHPINI}
