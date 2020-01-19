#!/bin/sh
#*********************************************************************
# set RW perm
#
# 2017-11-17 created
#*********************************************************************

BASE="/var/www/intra/laravel"

rwDir="bootstrap/cache storage/framework/cache storage/logs"

rwOwn="www-data"

if [ "$1" = "" ];then
    echo "Set rw perm to dir:"
for d in $rwDir
do
    echo "   $d"
done
echo "
   USAGE: $0 run
">&2
    exit
fi

mkRW()
{
dir=$1
    echo -n "set rw $dir "

    if [ -d $dir  ];then
	chown -R $rwOwn $dir

#        cd $dir || return
#        chmod 775 .
#        find . -type d -exec chmod 775 {} \;
#        find . -type f -exec chmod 664 {} \;
        echo OK
    else
        echo "skip"
    fi
}

for d in $rwDir
do
    mkRW $BASE/$d
done

exit







