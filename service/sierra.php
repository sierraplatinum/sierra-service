<?php
$cmd = "sh sierra.sh 1 test 2220 50000";
#shell_exec(sprintf('%s > /dev/null 2>&1 &', $cmd));
$pid = shell_exec(sprintf('%s > /dev/null 2>&1 & echo $!', $cmd));
#$who = shell_exec("whoami");
#echo $who;
#shell_exec("cd /local/sierra-service/");
#$dir = shell_exec("pwd");
echo $pid;
#shell_exec("screen -dmS sierra $cmd");
#shell_exec("$cmd");
?>

