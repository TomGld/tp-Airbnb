#!/usr/bin/sh
FILE=/adminLIDEM/${MYSQL_DATABASE}.sql
if [ -f $FILE ]; then
    rm $FILE 
fi
mariadb-dump ${MYSQL_DATABASE} -uroot -p${MYSQL_ROOT_PASSWORD} > $FILE
echo "Sauvegarde terminée"