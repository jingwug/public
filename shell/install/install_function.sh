#!/bin/bash

#create user
function c_useradd() {
	user=$1
	if [ "" == "`cat /etc/group | grep $user$`" ]; then
		groupadd $user
	fi

	if [ "" == "`cat /etc/passwd | grep $user$`" ]; then
		useradd -r -g $user $user
	fi 
}

#create dir
function c_mkdir() {
	dir=$1
	if [ ! -d $dir ]; then
		mkdir -p $dir	
	fi
}

