#!/bin/bash

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh

#add user
for user in "${users[@]}";
do
	c_useradd $user
done

#create dir
for dir in "${dirs[@]}";
do
	c_mkdir $dir	
done

#chown dirUsers
for dirUser in "${dirUsers[@]}";
do
	comm=(${dirUser/@@@@/ })
	dir=${comm[0]}
	user=${comm[1]}
	chown -R $user:$user $dir
done

#yum install
for yumInstall in "${yumInstalls[@]}";
do
	yum -y install $yumInstall
done


