#!/bin/bash

mysqladminBin="/usr/local/mysql5/bin/mysqladmin";
mysqlAuth="-uroot -pD8E5D2S3A6Q9A5S2";
#echo "$mysqladminBin $mysqlAuth processlist"
#kill 掉休眼时间大于10的Mysql进程
for pid in `$mysqladminBin $mysqlAuth processlist | grep -i sleep |  awk '{if($12>10){print $0}}' | awk '{print $2}'`
do
        #echo "$mysqladminBin $mysqlAuth kill $pid"
        $mysqladminBin $mysqlAuth kill $pid
done

