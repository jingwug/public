#!/bin/bash

date="$(date +%Y%m%d)"
year="$(date +%Y)"
month="$(date +%m)"
day="$(date +%d)"
wwwDir="/data0/www"
backDir="/data0/backup/www"

cd $wwwDir
for project in `ls -F ${wwwDir} | grep "/$"`
do
	project=${project/\//};
	if [ ! -d "${backDir}/${project}/${year}/${month}" ]; then
		mkdir -p ${backDir}/${project}/${year}/${month}
	fi
	tar -zcvf ${backDir}/${project}/${year}/${month}/${project}.${date}.tar.gz ${project}
done

