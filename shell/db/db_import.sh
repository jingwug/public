#!/bin/bash

for sqlfile in `ls /data0/script/rsysql`;
do
	database=${sqlfile/.sql/}
	/usr/local/mysql/bin/mysql -h 127.0.0.1 -uroot -pdbcaimiao\!@#246 ${database} < /data0/script/rsysql/${sqlfile}
done

