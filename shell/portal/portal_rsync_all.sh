#!/bin/bash

temp=`getopt -o d:s:h --long "dest:,src:,help" -- "$@" 2>/dev/null`
if [ $? != 0 ]; then echo "the params is not empty" >&2; exit 1;        fi
eval set -- "$temp"

function usage() {
    echo "The params is : ";
    echo "    --dest=RsyncDir    example: rsync@1.1.1.1::www/tester";
    echo "    --src=LocalDir     example: /data0/www/tester";
    shift
}
while true;
do
        [ -z "$1" ] && break;
        case $1 in
                -d|--dest)
                        dest="$2";
                        shift 2
                        ;;
                -s|--src)
                        src="$2";
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

if [ ! -d $src ]; then
        usage
        exit;
fi

if [ -d $dest ]; then
        usage
        exit;
fi

#srcDir="/websites/caimiao.cn/HTML"
#rsyncInfo="rsync@10.168.8.45::portal"
pwdFile="/etc/rsync_root_rsync.password"
inotifywait -mrq --timefmt '%Y%m%d%H%M%S' --format '%T %e %w%f' -e CLOSE_WRITE,delete,create,attrib ${src} | while read  file
do
        rsync --password-file=${pwdFile} -avz --progress --delete --exclude=\".svn/\" ${src}/ ${dest}
done

