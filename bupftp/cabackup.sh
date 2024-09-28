#!/bin/bash
PATH1=/srv/backups/ca
FILE1=ca_`date "+%Y%m%d%H%M%S"`.gz
FILENAME1=$PATH1/$FILE1
PATH2=/srv/backups/cae
FILE2=cae_`date "+%Y%m%d%H%M%S"`.gz
FILENAME2=$PATH2/$FILE2
/usr/bin/mysqldump -u doseok --password=kim7795004 ca | gzip > $FILENAME1
/usr/bin/mysqldump -u doseok --password=kim7795004 cae | gzip > $FILENAME2

#ftp to synology
#HOST="192.168.1.237"
HOST="cleanairsupply.synology.me 52306"
USER="cleanair"
PASSWORD=`echo ayFtZDAkMjVLCg== | base64 --decode`
ftp -inv $HOST <<EOF
user $USER $PASSWORD
lcd $PATH1
cd Backups/datadump/ca
put $FILE1
bye
EOF
ftp -inv $HOST <<EOF
user $USER $PASSWORD
lcd $PATH2
cd Backups/datadump/cae
mput $FILE2
bye
EOF
