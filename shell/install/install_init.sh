#!/bin/bash

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh


#add user
for user in "${users[@]}";
do

	if [ "" == "`cat /etc/group | grep $user`" ]; then
		groupadd $user
	fi
	if [ "" == "`cat /etc/passwd | grep $user`" ]; then
		useradd -r -g $user $user
	fi 
done

#create dir
for dir in "${dirs[@]}";
do

	if [ ! -d $dir ]; then
		mkdir $dir	
	fi
done

#chown dirUsers
for dirUser in "${dirUsers[@]}";
do
	comm=(${dirUser/@@@@/ })
	dir=${comm[0]}
	user=${comm[1]}
	chown -R $user $dir
done

#yum install
for yumInstall in "${yumInstalls[@]}";
do
	yum -y install $yumInstall
done

