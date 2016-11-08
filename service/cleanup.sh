#!/bin/sh

#clean up directory for finsihed servers

#if [ -e /proc/$PID ]
sqlite3 userdb.db "select ID, password, email, pid from user where running='true';" | while read line
do
	PID=$(echo $line | awk -F'|' '{ print $4 }');
	if [ ! -e /proc/$PID ]
	then
		id=$(echo $line | awk -F'|' '{ print $1 }');
		rm /local/sierra-service/data/$id.zip;
                cd /local/sierra-service/;
                zip /local/sierra-service/data/$id.zip data/$id/*.bed data/$id/*.csv data/$id/*.dsierra data/$id/*.stats;
                cd -;
		email=$(echo $line | awk -F'|' '{ print $3 }');
		pw=$(echo $line | awk -F'|' '{ print $2 }');
                echo "Your server for job $id and $pw was terminated. Please find attached the results of your job." | mail -a /local/sierra-service/data/$id.zip -r "sierra-service@seneca.informatik.uni-leipzig.de (Sierra Platinum Service)" -s "Sierra Server job $id terminated" $email;
                rm -r /local/sierra-service/data/$id /local/sierra-service/data/$id.properties /local/sierra-service/data/$id.log /local/sierra-service/data/$id.zip
                sqlite3 userdb.db "delete from user where id='$id'";
	fi

done

# Clean up step: kill server started more than 3 days ago and sent data to user, remove entries from db
sqlite3 userdb.db "select ID, password, email, started, pid from user where running='true';" | while read line
do
	started=$(echo $line | awk -F'|' '{ print $4 }');
	#echo $startedDB;
	#started=`date --date="$startedDB" +%s`;
	now=`date +%s`;
	diff=`echo "($now - $started)/(60 * 60 * 24)" | bc -l | awk '{print int($1)}'`;
	if [ $diff -ge 3 ]
	then
		pid=$(echo $line | awk -F'|' '{ print $5 }');
                id=$(echo $line | awk -F'|' '{ print $1 }');
		kill -9 $pid;
		rm /local/sierra-service/data/$id.zip;
		cd /local/sierra-service/data/;
		zip /local/sierra-service/data/$id.zip data/$id/*.bed data/$id/*.csv data/$id/*.dsierra data/$id/*.stats;
		cd -;
		email=$(echo $line | awk -F'|' '{ print $3 }');
		pw=$(echo $line | awk -F'|' '{ print $2 }');
		echo "Your server for job $id and $pw has reached is run time limit of 72 hours. Please find attached the results of your job." | mail -a /local/sierra-service/data/$id.zip  -r "sierra-service@seneca.informatik.uni-leipzig.de (Sierra Platinum Service) " -s "Sierra Server job $id terminated" $email;
		rm -r /local/sierra-service/data/$id /local/sierra-service/data/$id.properties /local/sierra-service/data/$id.log /local/sierra-service/data/$id.zip
		sqlite3 userdb.db "delete from user where id='$id'";
	fi
done

