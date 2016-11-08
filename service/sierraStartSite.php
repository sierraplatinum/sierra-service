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
        $csrf_token=md5(uniqid(rand(time()), true));
        setcookie("csrf_token", $csrf_token, time() + (60 * 15), "/");
        setcookie("origin","sierraStartSite");

	include("header.php");  
?>
    <article  style="position: absolute; left: 10px;width: 500px;">
      <div style="align: center; display: block; border: 1px solid rgb(204, 204, 204); margin: 0 auto; width: 500px; height: 493px;">
	<div style="padding: 10px; font-size: 12px; line-height: 16px; text-align: justify; font-family: Helvetica, Arial, FreeSans, sans-serif; color: #6e6e6e; word-break: normal;">	
	  <h1>Sierra Platinum</h1>
	  <p>DNA bound proteins such as transcription factors and modified histone proteins
	    play an important role in gene regulation.
	    Therefore, their genomic locations are of great interest.
	    Usually, the location is measured using ChIP-seq and analyzed using a peak-caller.
	    Replicated ChIP-seq experiments become more and more available.
	    Sierra Platinum allows
	    to call peaks for several replicates and also provides a variety of
	    quality measures.
	    Together with integrated visualizations, the quality measures
	    support the assessment of the replicates and the resulting peaks.
	    Sierra Platinum outperforms other methods.
	    We here provide <strong> Sierra Platinum Service</strong>: the first webserver for multi-replicate peak-calling. </p>

	  <h3>Usage Instruction</h3>
	  <ol>
	    <li>Provide an email-address and start your own Sierra
	      Platinum server for peak calling. We will notify you by
	      email once the server is ready to accept your
	      job. <font color="red">Your server will run 72 hours in
		total.</font></li>
	    <li>Download the Sierra client and connect to your Sierra
	      server (using the provided server settings).</li>
	    <li>Upload your data using the client and start peak
	      calling. You can improve the results by disabling or
	      downweighting replicates based on the visual quality
	      controls.</li>
	    <li>Download your result using the client.</li>
	  </ol>

	  
	  
	  <form align="center" action="createServer.php" method="POST">
            <?php 
		echo "<input type=\"hidden\" name=\"CRSFTOKEN\" value=\"$csrf_token\" required/>";
            ?>
	    E-Mail-Adresse: <input type="email" name="notify" required />
	    <input type="submit" value="Create My Sierra Server">
	  </form>
	  
	</div>
	<div style="position: absolute; right: -2px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div>
	<div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div>
      </div>
</article>    

    <article  style="position: absolute; left: 550px;width: 250px">
       <div style="align: center; display: block; border: 1px solid rgb(204, 204, 204); margin: 0 auto; width: 250px">
	<div style="padding: 10px; font-size: 12px; line-height: 16px; text-align: justify; font-family: Helvetica, Arial, FreeSans, sans-serif; color: #6e6e6e; word-break: normal;">	
	  <h1>Status Check</h1>
	  <p> If you already created your Sierra Platinum Server, you
	  can check the state by entering your job name and
	  password.</p>

       <form align="center" action="checkstatus.php" method="post">
            <?php
                //$csrf_token=md5(uniqid(rand(time()), true));
                echo "<input type=\"hidden\" name=\"CRSFTOKEN\" value=\"$csrf_token\" required/>";
                //setcookie("csrf_token", $csrf_token, time() + (60 * 15), "/");
                //setcookie("origin","sierraStartSite");
            ?>
	 Job Name: <input type="text" name="jobname" required /></br>
	 Password:  <input type="password" name="password" required /></br></br>
	    <input type="submit" value="Check Status">
	  </form>
	  
	</div>
	<div style="position: absolute; right: -2px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div>
	<div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div>
        </div>
    </article>

    

    <article  style="position: absolute; left: 550px; top: 400px; width: 250px">
       <div style="align: center; display: block; border: 1px solid rgb(204, 204, 204); margin: 0 auto; width: 250px">
        <div style="padding: 10px; font-size: 12px; line-height: 16px; text-align: justify; font-family: Helvetica, Arial, FreeSans, sans-serif; color: #6e6e6e; word-break: normal;">
          <h1>Cancel Job</h1>
          <p> Cancel your running or queued Sierra Server 
              using the job name and password:</p>

       <form align="center" action="canceljob.php" method="post">
            <?php
                //$csrf_token=md5(uniqid(rand(time()), true));
                echo "<input type=\"hidden\" name=\"CRSFTOKEN\" value=\"$csrf_token\" required/>";
                //setcookie("csrf_token", $csrf_token, time() + (60 * 15), "/");
                //setcookie("origin","sierraStartSite");
            ?>
         Job Name: <input type="text" name="jobname" required /></br>
         Password:  <input type="password" name="password" required /></br></br>
            <input type="submit" value="Cancel Job">
          </form>

        </div>
        <div style="position: absolute; right: -2px; top: -1px; width: 40px; height: 40px; border-style: solid; border-width: 3px 3px 0px 0px;">&nbsp;</div>
        <div style="position: absolute; left: 0px; bottom: -1px; width: 40px; height: 40px; border-style: solid; border-width: 0px 0px 3px 3px;">&nbsp;</div>
	</div>
    </article>




<?php include("footer.php");  ?>


    
