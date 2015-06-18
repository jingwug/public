#!/bin/bash
#nginx install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh


#clean old
rm -Rf $dirJiankongbaoSource
rm -Rf $dirSoft/$jiankongbaoZip
rm -Rf $jiankongbaoInstall


#install
wget -P $dirSoft $jiankongbaoUrl
unzip $dirSoft/$jiankongbaoZip -d $dirSoft
cp -R $dirJiankongbaoSource $dirJiankongbaoInstall
ln -s $dirJiankongbaoInstall $dirJiankongbaoLink
cd $dirJiankongbaoLink
sh start.sh
