#!/bin/bash

date="$(date +%Y%m%d)"
year="$(date +%Y)"
month="$(date +%m)"
day="$(date +%d)"
logDir="/data0/logs/php"

# access log
for filename in `ls $logDir | grep '.log'` 
do
	dirname=${filename/.log/}
	mkdir -p ${logDir}/${dirname}/${year}/${month}/${day}
	mv ${logDir}/${filename} ${logDir}/${dirname}/${year}/${month}/${day}/${filename/.log/}.${date}.log
	chown -R www:www ${logDir}/${dirname}
done

service php-fpm restart
