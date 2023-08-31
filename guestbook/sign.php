<?php 
  session_start();
  include "includes/functions.php"; 
  include "includes/globals.php";

  $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

  if (!$conn) {
  
    die("Connection failed: " . mysqli_connect_error());
  }

  $comCount = $sitetitle = $domain = "";

  $sql = "SELECT * FROM ".DBPREFIX."options";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {

		$row = $result->fetch_assoc(); 
		$comCount = $row["com_count"];

  } else {
		echo "0 results";
	}

  $sql = "SELECT * FROM ".DBPREFIX."settings";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {

		$row = $result->fetch_assoc();
    $sitetitle = $row["site_title"];
    $domain = $row["domain_name"];

  } else {
		echo "0 results";
	}


	$num1 = rand(1,9);
	$num2 = rand(1,9);

  $_SESSION["num3"] = $num1+$num2;

  if ($msg <> "") {
    displayFancyMsg(getMessage($msg));
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $sitetitle;  ?> | PHPGuestbook - Sign</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link type="text/css" rel="stylesheet" href="<?php echo GBDIR; ?>assets/css/jquery.fancybox.css" />
  <link type="text/css" rel="stylesheet" href="<?php echo GBDIR; ?>assets/css/main.css" />
</head>

<body>
<div id="main" class="container" style="margin-top:-50px;">
  <header style="text-align:center"><h2><?php echo $sitetitle; ?></h2></header>
  <div class="row">
    <div class="12u 12u(medium)" style="text-align:center;padding-bottom:10px;">
      <a class="button" style="background-color:#49639f;" href="<?php echo GBDIR; ?>view.php"><i class="fa fa-search"></i> View Guestbook</a>
    </div>
  </div>
  <div class="row">
    <div class="12u 12u$(medium)">
      <form action="includes/process.php" method="post">
      <input type="hidden" name="sign" value="yes" />
      <input type="hidden" name="IP" value="<?php echo $_SERVER["REMOTE_HOST"]; ?>" />
      <div class="row">
        <div class="-3u 6u 12u$(medium)">
          <div class="row">
<?php

  $sql = "SELECT * FROM ".DBPREFIX."fields";
  $result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		  if ($row["field_show"] == "yes") {
	      if ($row["field_name"] == "Site Visited") {
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
            <div class="select-wrapper">
              <?php selectSite(""); ?>
            </div> 
          </div>			
<?php		
			  } else if ($row["field_name"] == "Site Rating") {
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
            <div class="select-wrapper">
            <?php selectRate(0); ?>
            </div>
	          Please rate our site from 1 to 10, 10 being the best.
          </div>
<?php			
			  } else if ($row["field_name"] == "Find Us?") {
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
            <div class="select-wrapper">
              <?php selectFind(""); ?>
	          </div>
          </div>
<?php			
			 } else if ($row["field_name"] == "Comments") {
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
	          <textarea id="comments" name="comments" cols="30" rows="5" wrap="soft" onKeyDown="textCounter(this.form.comments,this.form.remLen,<?php echo $comCount; ?>);" onKeyUp="textCounter(this.form.comments,this.form.remLen,<?php echo $comCount; ?>);" placeholder="Comments: required" required></textarea>
            <br />
			      <input type="text" id="remLen" name="remLen" size="3" maxlength="3" value="<?php echo $comCount; ?>" readonly style="width:100px;">&nbsp;Characters left - HTML is not allowed!
          </div>
<?php			
			  } else if ($row["field_name"] == "Website") {
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
            <input id="website" name="<?php echo strtolower($row["field_name"]); ?>" placeholder="Website: Example: www.htmljunction.com or aspjunction.com" type="text" />
          </div>
<?php			
			  } else {
          $required = "";
          if (strtolower($row["field_name"]) == "name") { $required = "required";}
?>
          <div class="12u 12u$(medium)" style="padding-bottom:30px;">
            <input id="<?php echo strtolower($row["field_name"]); ?>" placeholder="<?php echo $row["field_name"].": ".$required; ?>" name="<?php echo strtolower($row["field_name"]); ?>" type="text" <?php echo $required; ?>/>
          </div>
<?php			
			  }
			}		
		}
	}
  $conn->close();
?>
          <div class="6u 12u$(small)" style="padding-bottom:30px;">
            <span style="color:#ff0000;font-size:20px;">*</span> <span style="font-size:20px;letter-spacing:.25em;vertical-align:middle;"><img src="<?php echo GBDIR; ?>images/<?php echo getNumtxt($num1); ?>" style="width:75px;height:37px;vertical-align:middle;" />
 + <img src="<?php echo GBDIR; ?>images/<?php echo getNumtxt($num2); ?>" style="width:75px;height:37px;vertical-align:middle;" />
 =</span>			      
            </div>
          <div class="2u$ 12u$(small)">            
            <input type="text" name="num3" class="cinput" maxlength="2" style="width:50px;" />
          </div>
          <div class="12u 12u$(medium)">
            <input style="background-color:#49639f;" type="submit" value="Sign Guest Book" />
          </div>
        </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
  <!-- REMOVAL, MODIFICATION OR CIRCUMVENTING THIS CODE WILL BREAK MY HEART PLEASE DON'T DO IT! -->
  <footer id="footer">
    <div class="copyright">
      Powered by <a href="http://www.phpjunction.com/webapps/">PHPGuestbook</a>  &copy; 2003 - <?php echo date("Y") ?> | <a href="http://phpjunction.com">PHP Junction</a>
    </div>
  </footer>
  <!-- REMOVAL, MODIFICATION OR CIRCUMVENTING THIS CODE WILL BREAK MY HEART PLEASE DON'T DO IT!  -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="../assets/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="../assets/js/skel.min.js"></script>
	<script type="text/javascript" src="../assets/js/baseline.js"></script>
  <script type="text/javascript" src="../assets/js/main.js"></script>
</body>
</html>