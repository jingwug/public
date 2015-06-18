#!/bin/bash

date="$(date +%Y%m%d)"
year="$(date +%Y)"
month="$(date +%m)"
day="$(date +%d)"
backDir="/data0/backup/mysql"
mysqlBin="/usr/local/mysql/bin/mysql"
mysqldumpBin="/usr/local/mysql/bin/mysqldump"
#mysqlAuth="-h 127.0.0.1 -uroot -p123456"
mysqlAuth="-uroot -p123456"

for database in `${mysqlBin} ${mysqlAuth} -s -e "show databases"`
do
	if [ "${database}" != "information_schema" ] && [ "${database}" != "performance_schema" ] && [ "${database}" != "mysql" ] && [ "${database}" != "test" ]; then
		mkdir -p ${backDir}/${database}/${year}/${month}
		${mysqldumpBin} ${mysqlAuth} ${database} | /bin/gzip > "${backDir}/${database}/${year}/${month}/${database}.${date}.gz"
	fi 
done

