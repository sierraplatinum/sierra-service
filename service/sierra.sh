#!/bin/bash

#echo $1;
#echo $2;
#echo $3;
#echo $4;
cd /local/sierra-service/;
mkdir data/$1;
#echo $(pwd);
java -jar -Xmx80G server.jar -service -user $1 -password $2 -FTP-port $3 -port $4 -passive $5 -threads $6 -homedir /local/sierra-service/data/$1 1> $1.log 2>$1.log & echo $!;

#echo "server started";
