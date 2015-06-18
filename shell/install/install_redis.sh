#!/bin/bash
#redis install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh

#clean old
rm -Rf $dirRedisInstall
rm -Rf $dirRedisLink
rm -Rf $dirRedisSource
rm -Rf $dirSoft/$redisTar

#install
wget -P $dirSoft $redisUrl 2>>/tmp/autoInstall.log
#${rsyncSoftBin}${redisTar} ${dirSoft}
tar -zxvf $dirSoft/$redisTar -C $dirSoft 1>>/tmp/autoInstall.log
cd $dirRedisSource
make PREFIX=$dirRedisInstall install 1>>/tmp/autoInstall.log 2>>/tmp/autoInstall.log
ln -s $dirRedisInstall $dirRedisLink
c_mkdir $dirRedisLink/etc
cp redis.conf $dirRedisLink/etc/redis_6379.conf
$dirRedisLink/bin/redis-server $dirRedisLink/etc/redis_6379.conf &

