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

	<?php include("header.php");  ?>	
<article  style="position: absolute; left: 10%;width: 500px"><div style="position: fixed; align: center; display: block; border: 1px solid rgb(204, 204, 204); margin: 0 auto; width: 500px"><div style="padding: 10px; font-size: 12px; line-height: 16px; text-align: justify; font-family: Helvetica, Arial, FreeSans, sans-serif; color: #6e6e6e; word-break: normal;">
    <?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
        
	//security code begin
	if(!(isset($_COOKIE['origin'])) || !($_COOKIE['origin'] == "sierraStartSite")){
		setcookie("origin","canceljob");
		exit("Either you do not accept cookies or you did not come from a valid page of origin. Your Job will not be processed.\n\n<p align=\"center\"><a href=\"sierraStartSite.php\">Back to main page</a></p>");
	}
	if(!(isset($_COOKIE['csrf_token'])) || !(isset($_POST['CRSFTOKEN'])) || !($_COOKIE['csrf_token'] == $_POST['CRSFTOKEN'])){
                setcookie("origin","canceljob");
                exit("You session cookie is expired or you hijacked this page! Your job request will not processed!\n\n<p align=\"center\"><a href=\"sierraStartSite.php\">Back to main page</a></p>");
        }
	setcookie("origin","canceljob");
	//security code end

	$jobname = $_POST["jobname"];
        $password = $_POST["password"];

       	echo "<h1>Job '$jobname' cancelation</h1>\n"; 
	$db = new SQLite3("$webserviceHome/userdb.db");
       
        $results = $db->query("SELECT ID as jobname, password as pw, started as start, running as status, pid, email from user where ID=\"$jobname\"");
	
	
        if($row = $results->fetchArray()){
        
         	
		if($row['pw'] == $password){
	   	if($row['status']){
			$pid = $row['pid'];
			$email = $row['email'];
			$cmd = "kill -9 $pid";
        		shell_exec(sprintf('%s > /dev/null 2>&1', $cmd));
			$cmd = "rm -r $sierraServiceDir/$jobname $sierraServiceDir/$jobname".".log $sierraServiceDir/$jobname".".properties";
			shell_exec(sprintf('%s > /dev/null 2>&1', $cmd));
			$message = "Dear Sierra Service User,\n\nyou canceled your Sierra Service Server for job '$jobname'.\n\nThanks for using Sierra Service!\n\nBest regards,\nSierra Platinum Service Team";
                	mail($email,"Your Sierra Server is canceled",$message,"From: Sierra Platinum Service <sierra-service@seneca.informatik.uni-leipzig.de>", "-f sierra-service@seneca.informatik.uni-leipzig.de");
			$db->exec("delete from user where ID='$jobname'");
			$runtime = time()-$row['start'];
			$runtime /= (60);
			echo "Server run ".round($runtime)." minutes (". (round($runtime / 60,1))." hours) before being canceled.</br>";
		}else{
			$db->exec("delete from user where ID='$jobname'");		
			echo "Server is removed from job queue.</br>";
		}
		}else{
	   	echo "<font color=\"red\">Invalid password for job $jobname!</br></font>\n";
		}
	}else{
	    echo "<font color=\"red\">No job with name $jobname!</br></font>\n";
        }	

        echo "<p align=\"center\"><a href=\"sierraStartSite.php\">Back to main page</a></p>";
	$db->close();
      ?>


	</br></br></div><div style="position: absolute; right: 0px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div><div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div></div></article>
    

<?php include("footer.php");  ?> 
