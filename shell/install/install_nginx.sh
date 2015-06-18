#!/bin/bash
#nginx install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh

sh $dirCurrent/install_before.sh
c_mkdir $dirNginxLog
chown -R $userWww:$userWww $dirNginxLog


#clean old
rm -Rf $dirNginxInstall
rm -Rf $dirNginxLink
rm -Rf $dirNginxSource
rm -Rf $dirSoft/$nginxTar


##install
wget -P $dirSoft $nginxUrl
#${rsyncSoftBin}${nginxTar} ${dirSoft}
tar -zxvf $dirSoft/$nginxTar -C $dirSoft
cd $dirNginxSource
./configure --prefix=$dirNginxInstall
make
make install
ln -s $dirNginxInstall $dirNginxLink
c_mkdir $dirNginxInstall/conf/vhost
rm -Rf $dirNginxLink/conf/nginx.conf
cp -Rf $dirCurrent/example_nginx.conf $dirNginxLink/conf/nginx.conf
cp $dirCurrent/example_nginx_vhost_demo.caimiao.com.conf $dirNginxInstall/conf/vhost/demo.caimiao.com.conf
cp $dirCurrent/example_nginx_service /etc/init.d/nginx
service nginx start

