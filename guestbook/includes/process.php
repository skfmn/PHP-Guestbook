<?php
  session_start();
  ob_start();
  include "functions.php";
	include "globals.php";

  $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
	
	$num3 = $sessNum = $ban = $sdate = "";
	$strName = $strEmail = $strWebsite = $strFacebook = "";
	$strTwitter = $strAge = $strLoc = $strSite = $strRate = $strFind = $strComments = $strIP = "";

  if (isset($_POST["sign"])) {
	
		$num3 = $_POST["num3"];
		$sessNum = $_SESSION["num3"];
		if ($num3 <> $sessNum) {
?>
		<script type="text/javascript">
		  alert("Check your addition!\n Please try again.")
		  window.location="../sign.php"
		</script>
<?php 
		} else {
			$strName = test_input($_POST["name"]);
			$strEmail = test_input($_POST["email"]);	
			$strWebsite = test_input($_POST["website"]);
			$strFacebook = test_input($_POST["facebook"]);
			$strTwitter = test_input($_POST["twitter"]);
			$strAge = test_input($_POST["age"]);
			$strLoc = test_input($_POST["location"]);
			$strSite = test_input($_POST["site"]);
			$strRate = test_input($_POST["rate"]);
			$strFind = test_input($_POST["find"]);
			$strComments = test_input($_POST["comments"]);
			$strIP = test_input($_POST["IP"]);

      If (strncasecmp($strWebsite,"http://",7)) {
        $strWebsite = str_replace("http://","",$strWebsite);
      }

      If (strncasecmp($strWebsite,"https://",8)) {
        $strWebsite = str_replace("https://","",$strWebsite);
      }

			$sql = "SELECT * FROM ".DBPREFIX."IP WHERE IP = '".$strIP."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {

				redirect($redirect."../sign.php?msg=banip");
				ob_end_flush();

			} else {

        $ban = "no";
				$sdate = date("Y/m/d");
				$stmt = $conn->prepare("INSERT INTO ".DBPREFIX."guestbook(name,email,website,facebook,twitter,age,loc,site,rate,find,IP,BanIP,gbdate,comments) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bind_param("ssssssssssssss", $strName, $strEmail, $strWebsite, $strFacebook, $strTwitter, $strAge, $strLoc, $strSite, $strRate, $strFind, $strIP, $ban, $sdate, $strComments);

				$stmt->execute();

				redirect($redirect."view.php?msg=sus");
				ob_end_flush();

			}

		}
  }
  $conn->close();
?>
