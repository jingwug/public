#!/bin/bash
#php install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh

c_mkdir $dirPhpLog
chown -R $userWww:$userWww $dirPhpLog

#clean old
rm -Rf $dirPhpInstall
rm -Rf $dirPhpLink
rm -Rf $dirPhpSource
rm -Rf $dirSoft/$phpTar
rm -Rf /etc/init.d/php-fpm

rm -Rf $dirZlibInstall
rm -Rf $dirZlibLink
rm -Rf $dirZlibSource
rm -Rf $dirSoft/$zlibTar

rm -Rf $dirJpegInstall
rm -Rf $dirJpegLink
rm -Rf $dirJpegSource
rm -Rf $dirSoft/$jpegTar

rm -Rf $dirPngInstall
rm -Rf $dirPngLink
rm -Rf $dirPngSource
rm -Rf $dirSoft/$pngTar

rm -Rf $dirVpxInstall
rm -Rf $dirVpxLink
rm -Rf $dirVpxSource
rm -Rf $dirSoft/$vpxTar

rm -Rf $dirFreetypeInstall
rm -Rf $dirFreetypeLink
rm -Rf $dirFreetypeSource
rm -Rf $dirSoft/$freetypeTar

rm -Rf $dirGd2Install
rm -Rf $dirGd2Link
rm -Rf $dirGd2Source
rm -Rf $dirSoft/$gd2Tar

rm -Rf $dirIconvInstall
rm -Rf $dirIconvLink
rm -Rf $dirIconvSource
rm -Rf $dirSoft/$iconvTar

rm -Rf $dirPhpMemcacheSource
rm -Rf $dirPhpRedisSource



#install zlib
wget -P $dirSoft $zlibUrl
#${rsyncSoftBin}${zlibTar} ${dirSoft}
tar -zxvf $dirSoft/$zlibTar -C $dirSoft
cd $dirZlibSource
./configure --prefix=$dirZlibInstall
make
make install
ln -s $dirZlibInstall $dirZlibLink

#install jpeg
wget -P $dirSoft $jpegUrl
#${rsyncSoftBin}${jpegTar} ${dirSoft}
tar -zxvf $dirSoft/$jpegTar -C $dirSoft
cd $dirJpegSource
./configure --prefix=$dirJpegInstall
make
make install
ln -s $dirJpegInstall $dirJpegLink

#install png
wget -P $dirSoft $pngUrl
#${rsyncSoftBin}${pngTar} ${dirSoft}
tar -zxvf $dirSoft/$pngTar -C $dirSoft
cd $dirPngSource
./configure --prefix=$dirPngInstall
make
make install
ln -s $dirPngInstall $dirPngLink

#install vpx
wget -P $dirSoft $vpxUrl
#${rsyncSoftBin}${vpxTar} ${dirSoft}
tar -zxvf $dirSoft/$vpxTar -C $dirSoft
cd $dirVpxSource
./configure --prefix=$dirVpxInstall
make
make install
ln -s $dirVpxInstall $dirVpxLink

#install freetype
wget -P $dirSoft $freetypeUrl
#${rsyncSoftBin}${freetypeTar} ${dirSoft}
tar -zxvf $dirSoft/$freetypeTar -C $dirSoft
cd $dirFreetypeSource
./configure --prefix=$dirFreetypeInstall
make
make install
ln -s $dirFreetypeInstall $dirFreetypeLink

#install gd2
wget -P $dirSoft $gd2Url
#${rsyncSoftBin}${gd2Tar} ${dirSoft}
tar -zxvf $dirSoft/$gd2Tar -C $dirSoft
cd $dirGd2Source
./configure --prefix=$dirGd2Install --with-freetype=$dirFreetypeLink --with-zlib=$dirZlibLink --with-png=$dirPngLink --with-jpeg=$dirJpegLink
make
make install
ln -s $dirGd2Install $dirGd2Link

#install iconv
wget -P $dirSoft $iconvUrl
#${rsyncSoftBin}${iconvTar} ${dirSoft}
tar -zxvf $dirSoft/$iconvTar -C $dirSoft
cd $dirIconvSource
./configure --prefix=$dirIconvInstall
make
make install
ln -s $dirIconvInstall $dirIconvLink

#install php
wget -P $dirSoft $phpUrl
#${rsyncSoftBin}${phpTar} ${dirSoft}
tar -zxvf $dirSoft/$phpTar -C $dirSoft
cd $dirPhpSource
./configure \
	--prefix=$dirPhpInstall \
	--with-config-file-path=$dirPhpInstall/etc \
	--with-mysql \
	--with-mysqli \
	--with-pdo-mysql \
	--disable-cgi \
	--enable-dba \
	--enable-hash \
	--enable-posix \
	--enable-libxml \
	--enable-xml \
	--enable-bcmath \
	--enable-sysvsem \
	--enable-inline-optimization \
	--enable-opcache \
	--enable-mbregex \
	--enable-fpm \
	--enable-mbstring \
	--enable-ftp \
	--enable-gd-native-ttf \
	--with-openssl \
	--enable-pcntl \
	--enable-sockets \
	--with-xmlrpc \
	--enable-zip \
	--enable-soap \
	--without-pear --disable-phar \
	--with-gettext \
	--enable-session \
	--with-curl \
	--enable-ctype \
	--enable-calendar \
	--with-mcrypt \
	--with-jpeg-dir=$dirJpegLink \
	--with-png-dir=$dirPngLink \
	--with-freetype-dir=$dirFreetypeLink \
	--with-zlib-dir=$dirZlibLink \
	--with-gd=$dirGd2Link \
	--with-iconv-dir=$dirIconvLink \
	--with-xpm-dir=/usr/lib64/
make
make install
ln -s $dirPhpInstall $dirPhpLink
c_mkdir $dirPhpLink/etc
cp $dirCurrent/example_php.ini $dirPhpInstall/etc/php.ini
cp $dirCurrent/example_php_php-fpm.conf $dirPhpInstall/etc/php-fpm.conf
cp $dirCurrent/example_php_php-fpm_service /etc/init.d/php-fpm

#install php memcahce
wget -P $dirSoft $phpMemcacheUrl
#${rsyncSoftBin}${phpMemcacheTar} ${dirSoft}
tar -zxvf $dirSoft/$phpMemcacheTar -C $dirSoft
cd $dirPhpMemcacheSource
$dirPhpLink/bin/phpize
./configure --with-php-config=$dirPhpInstall/bin/php-config
make
make install

#install php redis
wget -P $dirSoft $phpRedisUrl
#${rsyncSoftBin}${phpRedisTar} ${dirSoft}
tar -zxvf $dirSoft/$phpRedisTar -C $dirSoft
cd $dirPhpRedisSource
$dirPhpLink/bin/phpize
./configure --with-php-config=$dirPhpInstall/bin/php-config
make
make install

#wget -P $dirSoft $goPearUrl
#$dirPhpLink/bin/php $dirSoft/$goPearName

service php-fpm restart

