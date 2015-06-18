#!/bin/bash

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh

sh $dirCurrent/install_before.sh

#clean old
rm -Rf $dirMemcacheInstall
rm -Rf $dirMemcacheLink
rm -Rf $dirMemcacheSource
rm -Rf $dirSoft/$memcacheTar

#install
wget -P $dirSoft $memcacheUrl
#${rsyncSoftBin}${memcacheTar} ${dirSoft}
tar -zxvf $dirSoft/$memcacheTar -C $dirSoft
cd $dirMemcacheSource
./configure --prefix=$dirMemcacheInstall
make
make install
ln -s $dirMemcacheInstall $dirMemcacheLink
$dirMemcacheLink/bin/memcached -d -u root -m 512 -p 11211

