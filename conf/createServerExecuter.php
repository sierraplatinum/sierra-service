<?php

function findPort($db){
	for($port = sPort; $port <= ePort; $port++){
		$res = $db->query("Select count(*) as num from user where running='true' and port='$port'");
		$row = $res->fetchArray();
		if($row['num'] == 0){
			return($port);
		}	
	}
}

function findFTPPort($db){
	for($port = sFport; $port <= eFport; $port++){
                $res = $db->query("Select count(*) as num from user where running='true' and ftpPort='$port'");
                $row = $res->fetchArray();
                if($row['num'] == 0){
                        return($port);
                }       
        }
}

function startServers(){
	global $webserviceHome;
	global $sierraServiceDir;
	$cmd = "sh $webserviceHome/cleanup.sh";
	shell_exec(sprintf('%s > /dev/null 2>&1', $cmd));

	$maxServer = maxServers;
	$cores = maxThreads;
	$portRange = "pRange";

	$db = new SQLite3("$webserviceHome/userdb.db");

	$results = $db->query("Select count(*) as running from user where running='true'");
	$row = $results->fetchArray();
	$activeServers = $row['running'];

	$results = $db->query("Select count(*) as waiting from user where running IS NULL");
	$row = $results->fetchArray();
	$waitingServers = $row['waiting'];



	while($activeServers < $maxServer && $waitingServers > 0){
		$port = findPort($db);
		$ftpPort = findFTPPort($db);
		$results = $db->query("Select ID as jobname, password as pw, email from user where running IS NULL order by ID limit 1;");
		$row = $results->fetchArray();
		$jobname = $row['jobname'];
		$password = $row['pw'];
		$email = $row['email'];	
		$server=gethostname();
		$cmd = "sh $webserviceHome/sierra.sh $jobname $password $ftpPort $port $portRange $cores";
		$pid = shell_exec(sprintf('%s', $cmd));
		#$pid = 12938;
		$startTime=time();
		$db->exec("update user set pid='$pid',port='$port',ftpPort='$ftpPort',server='$server',running='true',started='$startTime' where ID=$jobname;");
		#echo "update user set pid=$pid,port=$port,ftpPort=$ftpPort,server='$server',running='true',started=strftime('%s',datetime()) where ID=$jobname";
		$message = "Dear Sierra Service User,\n\nyour server is running. You can log-in using the log-in credentials below.\njob name: $jobname\npassword: $password\nserver: $server\nport: $port\nFTP port: $ftpPort\n\nThanks for using Sierra Service!\n\nBest regards,\nSierra Platinum Service Team";
		mail($email,"Your Sierra Server is running",$message,"From: Sierra Platinum Service <sierra-service@seneca.informatik.uni-leipzig.de>", "-f sierra-service@seneca.informatik.uni-leipzig.de");
		#echo $message;
		$activeServers++;
		$waitingServers--;
	}
	$db->close();
}
?>

