#!/bin/bash
#nginx install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh


#clean old
rm -Rf $dirPsshSource
rm -Rf $dirSoft/$psshTar


#install
#wget -P $dirSoft $psshUrl
${rsyncSoftBin}${psshTar} ${dirSoft}
tar -zxvf $dirSoft/$psshTar -C $dirSoft
cd $dirPsshSource
python setup.py install

c_mkdir /home/pssh
cp -R $dirCurrent/example_pssh_home/.bash_history /home/pssh
cp -R $dirCurrent/example_pssh_home/.bash_logout /home/pssh
cp -R $dirCurrent/example_pssh_home/.bash_profile /home/pssh
cp -R $dirCurrent/example_pssh_home/.bashrc /home/pssh
cp -R $dirCurrent/example_pssh_home/.ssh /home/pssh
cp -R $dirCurrent/example_pssh_home/.viminfo /home/pssh
chown -R $userPssh /home/pssh
chmod -R 0600 /home/pssh/.ssh/*

service sshd restart

