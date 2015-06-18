#!/bin/bash

mysqladminBin="/usr/local/mysql5/bin/mysqladmin";
mysqlAuth="-uroot -pD8E5D2S3A6Q9A5S2";
#kill 掉锁表时间大于30的Mysql进程
for pid in `$mysqladminBin $mysqlAuth processlist | grep -i lock |  awk '{if($12>30){print $0}}' | awk '{print $2}'`
do
        $mysqladminBin $mysqlAuth kill $pid
done

