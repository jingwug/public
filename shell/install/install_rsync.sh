#!/bin/bash
#nginx install by JW

#import install_config.sh
dirCurrent=`dirname $(readlink -f "$0")`;
source $dirCurrent/install_config.sh
source $dirCurrent/install_function.sh
sh $dirCurrent/install_before.sh

#sed -i 's/disable\s*=\s*yes/disable = no/' /etc/xinetd.d/rsync
#not install xinted

c_mkdir $dirRsyncEtc
cp $dirCurrent/example_rsyncd.conf /etc/rsyncd.conf
cp $dirCurrent/example_rsyncd.password /etc/rsyncd.password
cp $dirCurrent/example_rsync_rsync.password /etc/rsync_root_rsync.password
cp $dirCurrent/example_rsync_rsync.password /etc/rsync_pssh_rsync.password
chmod 0600 /etc/rsyncd.password
chmod 0600 /etc/rsync_root_rsync.password
chmod 0600 /etc/rsync_pssh_rsync.password
chown root:root /etc/rsync_root_rsync.password
chown $userPssh:$userPssh /etc/rsync_pssh_rsync.password

rsync --daemon

