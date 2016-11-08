// **
// *****************************************************************************
// * Copyright (c) 2016 Daniel Gerighausen, Lydia Mueller
// *
// * Licensed under the Apache License, Version 2.0 (the "License");
// * you may not use this file except in compliance with the License.
// * You may obtain a copy of the License at
// *
// * http://www.apache.org/licenses/LICENSE-2.0
// *
// * Unless required by applicable law or agreed to in writing, software
// * distributed under the License is distributed on an "AS IS" BASIS,
// * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// * See the License for the specific language governing permissions and
// * limitations under the License.
// ******************************************************************************

<?php

function findPort($db){
	for($port = 20000; $port <= 20020; $port++){
		$res = $db->query("Select count(*) as num from user where running='true' and port='$port'");
		$row = $res->fetchArray();
		if($row['num'] == 0){
			return($port);
		}	
	}
}

function findFTPPort($db){
	for($port = 10000; $port <= 10010; $port++){
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

	$maxServer = 3;
	$cores = 6;
	$portRange = "20000-20020";

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

