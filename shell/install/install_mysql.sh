#!/bin/bash

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh

#clean old
rm -Rf $dirMysqlInstall
rm -Rf $dirMysqlLink
rm -Rf $dirMysqlSource
rm -Rf $dirSoft/$mysqlTar
rm -Rf /etc/init.d/mysql

wget -P $dirSoft $mysqlUrl
#${rsyncSoftBin}${mysqlTar} ${dirSoft}
tar -zxvf $dirSoft/$mysqlTar -C $dirSoft
cd $dirMysqlSource
cmake -DCMAKE_INSTALL_PREFIX=$dirMysqlInstall -DMYSQL_DATADIR=$dirMysqlData -DSYSCONFDIR=$dirMysqlInstall/etc -DWITH_MYISAM_STORAGE_ENGINE=1 -DWITH_INNOBASE_STORAGE_ENGINE=1 -DWITH_MEMORY_STORAGE_ENGINE=1 -DWITH_READLINE=1 -DMYSQL_UNIX_ADDR=$dirMysqlInstall/mysql.sock -DMYSQL_TCP_PORT=3306 -DENABLED_LOCAL_INFILE=1 -DWITH_PARTITION_STORAGE_ENGINE=1 -DEXTRA_CHARSETS=all -DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci
make
make install
ln -s $dirMysqlInstall $dirMysqlLink

c_useradd $userMysql

touch $dirMysqlLink/mysqld.pid

c_mkdir $dirMysqlLink/etc
chown -R $userMysql:$userMysql $dirMysqlLink

c_mkdir $dirMysqlData
chown -R $userMysql:$userMysql $dirMysqlData

c_mkdir $dirMysqlLog
chown -R $userMysql:$userMysql $dirMysqlLog

$dirMysqlInstall/scripts/mysql_install_db --basedir=$dirMysqlInstall --user=mysql
mv $dirMysqlLink/my.cnf $dirMysqlLink/etc/
rm -Rf $dirMysqlLink/my.cnf
cp $dirCurrent/example_mysql_my.cnf $dirMysqlLink/etc/my.cnf
#执行完上步后会产生my.cnf文件，编辑此文件，示例见install_mysql_cnf.txt
chown -R $userMysql:$userMysql $dirMysqlInstall
cp $dirCurrent/example_mysql.server /etc/init.d/mysql

scriptname=$(basename $0)
ps -ef | grep mysql | grep -v ${scriptname/.sh/} | grep -v grep | awk '{print $2}' | xargs kill -9
rm -Rf $dirMysqlLink/mysqld.pid
touch $dirMysqlLink/mysqld.pid

service mysql start

