#!/bin/bash

wwwDirs=(
#/websites/caimiao.cn.bbs
#/websites/caimiao.cn.baike
#/websites/caimiao.cn.uce
#/websites/caimiao.cn.user
#/websites/caimiao.cn
)

year=$(date '+%Y')
month=$(date '+%m')
day=$(date '+%d')
date=$(date '+%Y%m%d')
localIp=$(ifconfig | grep 'inet addr:10' | awk '{print $2}' | awk -F":" '{print $2}')
localDir="/data0/backup/www/"

for dir in "${wwwDirs[@]}";
do
	cd $dir
	basename=$(basename $dir)
	dirRelative=${year}/${month}/${day}
	dirAbsolute=${localDir}${year}/${month}/${day}
	mkdir -p $dirAbsolute
	tar -zcvf ${dirAbsolute}/${basename}.${date}.tar.gz HTML/* --exclude .svn
done

rsync --password-file=/etc/rsync_root_rsync.password -avz --progress ${localDir} rsync@10.168.8.45::backup/www/${localIp}

