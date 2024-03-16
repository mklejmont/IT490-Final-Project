#!/bin/bash

crontab -l > ./mycronjobs
path=`pwd`
echo "00 00 * * 1-7 python $path/dbcron.py" >> ./mycronjobs
crontab ./mycronjobs
rm -rf ./mycronjobs