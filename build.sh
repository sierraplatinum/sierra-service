#!/usr/bin/env bash
DOCKERFILE_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )


echo -n "Enter mail adress to send notifications [ENTER]: "
read mail
echo -n "Enter mailserver domain [ENTER]: "
read domain
pwcheck()
{
echo "Enter password for mail adress [ENTER]: ";
read -s password
echo  "Retype password for mail adress [ENTER]: ";
read -s passcheck
}

pwcheck;
while [ $password != $passcheck ]
do
  echo  "Password missmatch!";
  pwcheck;
done
echo -n "Enter start of server port range [ENTER]: "
read sPort
echo -n "Enter end of server port range [ENTER]: "
read ePort

echo -n "Enter start of FTP port range [ENTER]: "
read sFport

echo -n "Enter end of FTP port range [ENTER]: "
read eFport

echo -n "Enter maximum possible servers [ENTER]: "
read maxServer

echo -n "Enter threads per server [ENTER]: "
read threads


ports=$(expr $ePort - $sPort);
max=$(expr $maxServer \* 2);
if (("$ports" <  "$maxServer"))
then {
echo $ports;
echo $max;
echo "The server port range must be atleast two times greater than the possible servers due passive FTP!";

exit;
}
fi
fports=$(expr $eFport - $sFport)
if(("$fports" <  "$maxServer"));
then {
echo "The FTP port range must be atleast greater than the possible servers!";

exit;
}
fi




mkdir etc/ssmtp/
cat conf/ssmtp.conf |sed 's/mail@domain.tld/'$mail'/g' | sed 's/domain.tld/'$domain'/g' | sed 's/password/'$password'/g' > etc/ssmtp/ssmtp.conf;
cat conf/revaliases |sed 's/mail@domain.tld/'$mail'/g' | sed 's/domain.tld/'$domain'/g' > etc/ssmtp/revaliases
cat conf/createServerExecuter.php | sed 's/sPort/'$sPort'/g' | sed 's/ePort/'$ePort'/g' | sed 's/sFport/'$sFport'/g' | sed 's/eFport/'$eFport'/g' | sed 's/maxServers/'$maxServer'/g' | sed 's/pRange/'$sPort'-'$ePort'/g' | sed 's/maxThreads/'$threads'/g' > service/createServerExecuter.php
cat conf/run.sh |  sed 's/sPort/'$sPort'/g' | sed 's/ePort/'$ePort'/g' | sed 's/sFport/'$sFport'/g' | sed 's/eFport/'$eFport'/g' > run.sh
cat conf/Dockerfile | sed 's/ftpPorts/'$sFport'-'$eFport'/g' | sed 's/tcpPorts/'$sPort'-'$ePort'/g' > Dockerfile
docker build -t sierra-service $DOCKERFILE_DIR
