#!/bin/bash

date="$(date +%Y%m%d)"
year="$(date +%Y)"
month="$(date +%m)"
day="$(date +%d)"
logDir="/data0/logs/mysql"

# access log
for filename in `ls $logDir` 
do
	dirname=${filename/.log/}
	if [ ! -d $dirname ]; then
		mkdir -p "${logDir}/${dirname}"
	fi
	mkdir -p "${logDir}/${dirname}/${year}/${month}/${day}"
	mv ${logDir}/${filename} ${logDir}/${dirname}/${year}/${month}/${day}/${filename/.log/}.${date}.log
	chown -R www:www "${logDir}/${dirname}"
done

service mysql restart
