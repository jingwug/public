#!/bin/bash
#create By JW

centerIp="10.168.8.45"
rsyncSoftBin="rsync --password-file=/etc/rsync_root_rsync.password -avz --progress --delete rsync@10.168.8.45::soft/"

#common dir
#echo $dirCurrent
dirLog="/data0/logs"
dirWww="/data0/www"
dirBackup="/data0/backup"
dirScript="/data0/script"
dirSoft="/data0/softtester" 
dirCaimiao="/usr/local/caimiao" 

#common user
userWww="www"
userPssh="pssh"
userMysql="mysql"
userRsync="rsync"

#mysql
#mysqlUrl="http://cdn.mysql.com/Downloads/MySQL-5.6/mysql-5.6.23.tar.gz";
mysqlUrl="http://soft.caimiao.com/mysql-5.6.23.tar.gz";
mysqlTar="mysql-5.6.23.tar.gz";
dirMysqlSource=$dirSoft/${mysqlTar/.tar.gz/}
dirMysqlLink="/usr/local/mysql"
dirMysqlInstall="/usr/local/caimiao/mysql5623"
dirMysqlData="/data0/data_running/mysql"
dirMysqlLog="/data0/logs/mysql"

#memcache
#memcacheUrl="http://www.memcached.org/files/memcached-1.4.22.tar.gz"
memcacheUrl="http://soft.caimiao.com/memcached-1.4.22.tar.gz"
memcacheTar="memcached-1.4.22.tar.gz"
dirMemcacheSource=$dirSoft/${memcacheTar/.tar.gz/}
dirMemcacheLink="/usr/local/memcache"
dirMemcacheInstall="/usr/local/caimiao/memcache1422"

#redis
#redisUrl="http://download.redis.io/releases/redis-2.8.19.tar.gz"
redisUrl="http://soft.caimiao.com/redis-2.8.19.tar.gz"
redisTar="redis-2.8.19.tar.gz"
dirRedisSource=$dirSoft/${redisTar/.tar.gz/}
dirRedisLink="/usr/local/redis"
dirRedisInstall="/usr/local/caimiao/redis2819"

#nginx
#nginxUrl="http://nginx.org/download/nginx-1.7.10.tar.gz"
nginxUrl="http://soft.caimiao.com/nginx-1.7.10.tar.gz"
nginxTar="nginx-1.7.10.tar.gz"
dirNginxSource=$dirSoft/${nginxTar/.tar.gz/}
dirNginxLink="/usr/local/nginx"
dirNginxInstall="/usr/local/caimiao/nginx1710"
dirNginxLog="/data0/logs/nginx"

#php
#phpUrl="http://cn2.php.net/distributions/php-5.5.22.tar.gz"
phpUrl="http://soft.caimiao.com/php-5.5.22.tar.gz"
phpTar="php-5.5.22.tar.gz"
dirPhpSource=$dirSoft/${phpTar/.tar.gz/}
dirPhpLink="/usr/local/php5"
dirPhpInstall="/usr/local/caimiao/php5522"
dirPhpLog="/data0/logs/php"

#php zlib
#zlibUrl="http://prdownloads.sourceforge.net/libpng/zlib-1.2.8.tar.gz"
zlibUrl="http://soft.caimiao.com/zlib-1.2.8.tar.gz"
zlibTar="zlib-1.2.8.tar.gz"
dirZlibSource=$dirSoft/${zlibTar/.tar.gz/}
dirZlibLink="/usr/local/zlib"
dirZlibInstall="/usr/local/caimiao/zlib128"

#php jpeg
#jpegUrl="http://ijg.org/files/jpegsrc.v8d.tar.gz"
jpegUrl="http://soft.caimiao.com/jpegsrc.v8d.tar.gz"
jpegTar="jpegsrc.v8d.tar.gz"
dirJpegSource="$dirSoft/jpeg-8d"
dirJpegLink="/usr/local/jpeg"
dirJpegInstall="/usr/local/caimiao/jpegv8d"

#php libpng
#pngUrl="http://prdownloads.sourceforge.net/libpng/libpng-1.6.16.tar.gz"
pngUrl="http://soft.caimiao.com/libpng-1.6.16.tar.gz"
pngTar="libpng-1.6.16.tar.gz"
dirPngSource=$dirSoft/${pngTar/.tar.gz/}
dirPngLink="/usr/local/png"
dirPngInstall="/usr/local/caimiao/png1616"

#php libvpx
#vpxUrl="http://anduin.linuxfromscratch.org/sources/other/libvpx-v1.3.0.tar.gz"
vpxUrl="http://soft.caimiao.com/libvpx-v1.3.0.tar.gz"
vpxTar="libvpx-v1.3.0.tar.gz"
dirVpxSource=$dirSoft/${vpxTar/.tar.gz/}
dirVpxLink="/usr/local/vpx"
dirVpxInstall="/usr/local/caimiao/vpx130"

#php freetype
#freetypeUrl="http://download.savannah.gnu.org/releases/freetype/freetype-2.4.0.tar.gz"
freetypeUrl="http://soft.caimiao.com/freetype-2.4.0.tar.gz"
freetypeTar="freetype-2.4.0.tar.gz"
dirFreetypeSource=$dirSoft/${freetypeTar/.tar.gz/}
dirFreetypeLink="/usr/local/freetype"
dirFreetypeInstall="/usr/local/caimiao/freetype240"

#php gd2
#gd2Url="https://bitbucket.org/libgd/gd-libgd/downloads/libgd-2.1.1.tar.gz"
gd2Url="http://soft.caimiao.com/libgd-2.1.1.tar.gz"
gd2Tar="libgd-2.1.1.tar.gz"
dirGd2Source=$dirSoft/${gd2Tar/.tar.gz/}
dirGd2Link="/usr/local/gd2"
dirGd2Install="/usr/local/caimiao/gd211"

#php libiconv
#iconvUrl="http://ftp.gnu.org/pub/gnu/libiconv/libiconv-1.14.tar.gz"
iconvUrl="http://soft.caimiao.com/libiconv-1.14.tar.gz"
iconvTar="libiconv-1.14.tar.gz"
dirIconvSource=$dirSoft/${iconvTar/.tar.gz/}
dirIconvLink="/usr/local/libiconv"
dirIconvInstall="/usr/local/caimiao/libiconv114"

#php memcache
#phpMemcacheUrl="http://pecl.php.net/get/memcache-3.0.8.tgz"
phpMemcacheUrl="http://soft.caimiao.com/memcache-3.0.8.tgz"
phpMemcacheTar="memcache-3.0.8.tgz"
dirPhpMemcacheSource=$dirSoft/${phpMemcacheTar/.tgz/}

#php redis
#phpRedisUrl="http://pecl.php.net/get/redis-2.2.5.tgz"
phpRedisUrl="http://soft.caimiao.com/redis-2.2.5.tgz"
phpRedisTar="redis-2.2.5.tgz"
dirPhpRedisSource=$dirSoft/${phpRedisTar/.tgz/}

#PHP_Archive
#goPearUrl="http://pear.php.net/go-pear.phar"
goPearUrl="http://soft.caimiao.com/go-pear.phar"
goPearName="go-pear.phar"

#rsync
dirRsyncEtc="/etc"

#pssh
#psshUrl="http://files.opstool.com/files/pssh-2.3.tar.gz"
psshUrl="http://soft.caimiao.com/pssh-2.3.tar.gz"
psshTar="pssh-2.3.tar.gz"
dirPsshSource=$dirSoft/${psshTar/.tar.gz/}

#jiankongbao
#jiankongbaoUrl="http://www.jiankongbao.com/agent_down.php?type=linux&key=fffda290b4000000916200003066303286e254a44c4f3ff20821"
jiankongbaoUrl="http://soft.caimiao.com/jkb_agent_linux.zip"
jiankongbaoZip="jkb_agent_linux.zip"
dirJiankongbaoSource=$dirSoft/${jiankongbaoZip/_linux.zip/}
dirJiankongbaoLink="/usr/local/jiankongbao"
dirJiankongbaoInstall="/usr/local/caimiao/jiankongbao"

#config
users=($userWww $userPssh $userMysql $userRsync)
dirs=($dirLog $dirWww $dirBackup $dirScript $dirSoft $dirCaimiao)
dirUsers=($dirWww@@@@$userWww)
yumInstalls=(gcc gcc-c++ autoconf automake make ncurses-devel cmake rsync git 'inotify-tools --enablerepo=epel' yasm pcre-devel zlib-devel libevent-devel openssl-devel curl-devel libmcrypt libmcrypt-devel curl-devel mysql-devel tcl  net-snmp-devel libaio gzip libjpeg-devel libXpm-devel libxml2-devel libpng-devel python)

