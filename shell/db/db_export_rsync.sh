#!/bin/bash

temp=`getopt -o p:r:d::h --long "passwd::,rsync::,database::help" -- "$@" 2>/dev/null`
if [ $? != 0 ]; then echo "the params is not empty" >&2; exit 1;        fi
eval set -- "$temp"

function usage() {
    echo "The params is : ";
    echo "    --passwd=xxxx           mysql root passwd; example: 123456";
    echo "    --rsync=rsyncDestDir    rsync desc dir; example: 10.10.10.10::script/rsysql";
    echo "    --database=database     mysql database; example: cm_bbs";
}

while true;
do
        [ -z "$1" ] && break;
        case $1 in
                -p|--passwd)
                        passwd="$2";
                        shift 2
                        ;;
                -r|--rsync)
                        rsync="$2";
                        shift 2
                        ;;
                -d|--database)
                        database="$2";
                        shift 2
                        ;;
                --)
                        shift
                        break
                        ;;
                -h|--help)
                        usage
                        exit; 
                        ;;
        esac
done


if [ "" == "${passwd}" ]; then
        usage
        exit;
fi

if [ "" == "${rsync}" ]; then
        usage
        exit;
fi

if [ "${database}" == "information_schema" ] || [ "${database}" == "performance_schema" ] || [ "${database}" == "mysql" ] && [ "${database}" == "test" ]; then
	database=""
fi

date="$(date +%Y%m%d)"
year="$(date +%Y)"
month="$(date +%m)"
day="$(date +%d)"
backDir="/data0/backup/mysql"
mysqlBin="/usr/local/mysql/bin/mysql"
mysqldumpBin="/usr/local/mysql/bin/mysqldump"
mysqlAuth='-h 127.0.0.1 -uroot -pdbcaimiao!@#130'
#mysqlAuth='-uroot -pdbcaimiao\!@#130'
sourceDir="/data0/script/rsysql/"

if [ ""=="${database}" ]; then
	for database in `${mysqlBin} ${mysqlAuth} -s -e "show databases;"`
	do
		if [ "${database}" != "information_schema" ] && [ "${database}" != "performance_schema" ] && [ "${database}" != "mysql" ] && [ "${database}" != "test" ]; then
			${mysqldumpBin} ${mysqlAuth} ${database} > ${sourceDir}${database}.sql
			rsync --password-file=/etc/rsync_root_rsync.password -avz --progress ${sourceDir}${database}.sql rsync@${rsync}/${database}.sql
		fi
	done
else
	${mysqldumpBin} ${mysqlAuth} ${database} > ${sourceDir}${database}.sql
	rsync --password-file=/etc/rsync_root_rsync.password -avz --progress ${source}${database}.sql rsync@${rsync}/${database}.sql
fi

