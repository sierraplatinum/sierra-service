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

