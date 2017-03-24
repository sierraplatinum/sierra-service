	<?php include("header.php");  ?>	
<article  style="position: absolute; left: 10%;width: 500px"><div style="position: fixed; align: center; display: block; border: 1px solid rgb(204, 204, 204); margin: 0 auto; width: 500px"><div style="padding: 10px; font-size: 12px; line-height: 16px; text-align: justify; font-family: Helvetica, Arial, FreeSans, sans-serif; color: #6e6e6e; word-break: normal;">
    <?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
        include("createServerExecuter.php");
	//security code begin
        if(!(isset($_COOKIE['origin'])) || !($_COOKIE['origin'] == "sierraStartSite")){
                setcookie("origin","createServer");
                exit("Either you do not accept cookies or you did not come from a valid page of origin. Your Job will not be processed.\n\n<p align=\"center\"><a href=\"sierraStartSite.php\">Back to main page</a></p>");
        }
        if(!(isset($_COOKIE['csrf_token'])) || !(isset($_POST['CRSFTOKEN'])) || !($_COOKIE['csrf_token'] == $_POST['CRSFTOKEN'])){
                setcookie("origin","createServer");
                exit("You seem to hijack this page! Your job request will not processed!\n\n<p align=\"center\"><a href=\"sierraStartSite.php\">Back to main page</a></p>");
        }
        setcookie("origin","createServer");
        //security code end



	$notifyEmail = $_POST['notify'];


	$db = new SQLite3('userdb.db'); 
	$query = "SELECT * FROM registered_users where email = '$notifyEmail'";
	$results = $db->query("Select *  from registered_users where email='$notifyEmail'");
	$row = $results->fetchArray();

	if(empty($row)) {
	//echo "Mail unknown";
	$hash = md5($notifyEmail);
	$db->query("insert into registered_users (email, hash) values ('$notifyEmail','$hash')");
	 $results = $db->query("Select *  from registered_users where email='$notifyEmail'");
        $row = $results->fetchArray();
        $id = $row["hash"];
	$actual_link = "http://$_SERVER[HTTP_HOST]"."/activateServer.php?id=" . $id;	
	//echo $actual_link;
	$message = "Dear Sierra Service User,\nPlease activate your server by clicking the following link:\n$actual_link\nThanks for using Sierra Service!\n\nBest regards,\nSierra Platinum Service Team";
		mail($notifyEmail,"Sierra Server Activation Link",$message,"From: Sierra Platinum Service <sierra-service@seneca.informatik.uni-leipzig.de>", "-f sierra-service@seneca.informatik.uni-leipzig.de");
        echo "The activation link was sent to your email adress!";

	}
	$db->close();
      ?>


	</br></br></div><div style="position: absolute; right: 0px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div><div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div></div></article>
    

<?php include("footer.php");  ?> 
