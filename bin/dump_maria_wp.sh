#!/bin/sh

echo "ensure pwd TOP\ncat /etc/php.ini | egrep '(mysqli.default_user|mysqli.default_pw)' | grep -v get_cfg"
#export USER=$(sudo -iu devel ssh happytailspawcare.com cat /etc/php.ini | egrep '(mysqli.default_user|mysqli.default_pw)' | grep -v get_cfg
#export USER=$(sudo -iu devel ssh happytailspawcare.com cat /etc/php.ini | egrep 'mysqli.default_user|mysqli.default_pw
mariadb-dump -u wp -p -h happytailspawcare.com wp > data/wp.sql
