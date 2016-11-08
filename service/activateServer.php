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
	 include("createServerExecuter.php");
	$db = new SQLite3('userdb.db'); 
        //create entry in database
	$result = $db->query("Select * from registered_users where hash='" . $_GET["id"]. "'");
	$row = $result->fetchArray();
	if(!empty($row))
	{
	$hash = $row["hash"];
	echo "Hash found!";
	$notifyEmail = $row["email"];
	echo $notifyEmail;
	$db->query("insert into user (password,email) values (hex(randomblob(8)),'$notifyEmail')");
	// get ID of inserted job
	$results = $db->query("select distinct last_insert_rowid() as last from user;");
	$row = $results->fetchArray();
	$jobname = $row['last'];
	// get password
	$results = $db->query("Select password as pw from user where ID='$jobname'");
	$row = $results->fetchArray();
	$password = $row["pw"];	

	echo "<h1>Job request with job name '$jobname'</h1>";
	echo "<p>Your job request for a Sierra Server is submitted 
             to the job queue. As soon as you server is started, 
             we will send a notification e-mail with the log-in credentials 
             for your Sierra Server to '$notifyEmail'. Once you have your 
             log-in credentials, you can connect the Sierra Client to your 
             Sierra Service for file upload and peak calling. Note that your 
             Sierra Server will be automatically termianted after 72 hours run time.</p>";
	echo "<p>You can use the 'Check Status' box to check whether your job request is 
                  still queued, running, or terminated. Your job name is '$jobname' and your 
                  password is '$password'. </p>";
        echo "<p align=\"center\"><a href=\"sierra-client.jar\">Download Sierra Client</a>&nbsp;&nbsp;<a href=\"sierraStartSite.php\">Back to main page</a></p>";
	$db->query("delete from registered_users where hash='$hash'");
	$db->close();
	startServers();
	}
	else
	{
	echo "Activation Link unknown!";
	}
      ?>


	</br></br></div><div style="position: absolute; right: 0px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div><div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div></div></article>
    

<?php include("footer.php");  ?> 
