#!/bin/bash

temp=`getopt -o p:u:h --long "project:,update,help" -- "$@" 2>/dev/null`
if [ $? != 0 ]; then echo "the params is not empty" >&2; exit 1;        fi
eval set -- "$temp"

function usage() {
    echo "The params is : ";
    echo "    --project=project name    example: baike";
    echo "project have : ";
    echo "    tester,baike,finance";
    shift
}
while true;
do
        [ -z "$1" ] && break;
        case $1 in
                -p|--project)
                        project="$2";
                        shift 2
                        ;;
                -u|--update)
                        update=1;
                        shift 2
                        ;;
                --)
                        shift
                        break
                        ;;
                *|-h|--help)
                        usage
                        exit; 
                        ;;
        esac
done


logFile="/tmp/publish.log"
svnCodeDir="/data0/svncode"
rsyncBin="rsync --password-file=/etc/rsync_www_rsync.password -avz --progress --delete --exclude .svn"

echo '' > $logFile
if [ "$project" == "tester" ]; then
	svn update --username=publish --password=publish --no-auth-cache /data0/svncode/baike
	${rsyncBin} ${svnCodeDir}/baike/ rsync@10.251.235.143::tester
	cat $logFile
	exit;
fi

if [ "$project" == "baike" ]; then
	if [ "$update" == "1" ]; then
		svn update --username=publish --password=publish --no-auth-cache /data0/svncode/baike
	fi
	echo "" >> $logFile
	echo "" >> $logFile
	echo "" >> $logFile
	if [ "$update" != "1" ]; then
		${rsyncBin} --exclude config --exclude data ${svnCodeDir}/baike/ rsync@10.168.235.192::baike
	fi
	exit;
fi

usage;

