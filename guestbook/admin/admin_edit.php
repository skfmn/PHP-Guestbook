<?php
session_start();
ob_start();
include '../includes/functions.php';
include '../includes/globals.php';

$cookies = "";
$cookies = $_SESSION["gbadminname"];

if ($cookies == "") {

  redirect($redirect . "admin/admin_login.php");
  ob_end_flush();

}

if (isset($_SESSION["msg"])) {
  $msg = $_SESSION["msg"];
  if ($msg <> "") {
    displayFancyMsg(getMessage($msg));
    $_SESSION["msg"] = "";
  }
}

$conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if (!$conn) {

  die("Connection failed: " . mysqli_connect_error());
}

$num3 = $sessNum = $ban = $datDate = "";
$strName = $strEmail = $strWebsite = $strFacebook = "";
$strTwitter = $strAge = $strLoc = $strSite = $strRate = $strFind = $strComments = $strIP = "";

$blnBanned = false;
$guestbookID = 0;

if (isset($_GET["gid"])) {
  $guestbookID = test_input($_GET["gid"]);
}

if (isset($_POST["edit"])) {

  $strName = test_input($_POST["name"]);
  $strEmail = test_input($_POST["email"]);
  $strWebsite = test_input($_POST["website"]);
  $strFaceBook = test_input($_POST["facebook"]);
  $strTwitter = test_input($_POST["twitter"]);
  $strAge = test_input($_POST["age"]);
  $strLoc = test_input($_POST["loc"]);
  $strSite = test_input($_POST["site"]);
  $strFind = test_input($_POST["find"]);
  $strRate = test_input($_POST["rate"]);
  $strComments = test_input($_POST["comments"]);

  $stmt = $conn->prepare("UPDATE " . DBPREFIX . "guestbook SET name = ?, email = ?, website = ?, facebook = ?, twitter = ?, age = ?, loc = ?, site = ?, find = ?, rate = ?, comments = ?  WHERE guestbookID = ?");
  $stmt->bind_param("ssssssssssss", $strName, $strEmail, $strWebsite, $strFaceBook, $strTwitter, $strAge, $strLoc, $strSite, $strFind, $strRate, $strComments, $guestbookID);
  $stmt->execute();

  $_SESSION["msg"] = "ened";
  redirect($redirect . "admin/admin_entries.php");
  ob_end_flush();

}

$stmt = $conn->prepare("SELECT * FROM " . DBPREFIX . "guestbook WHERE guestbookID = ?");
$stmt->bind_param("s", $guestbookID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();

  $strName = $row["name"];
  $strEmail = $row["email"];
  $strWebsite = $row["website"];
  $strFacebook = $row["facebook"];
  $strTwitter = $row["twitter"];
  $strAge = $row["age"];
  $strLoc = $row["loc"];
  $strSite = $row["site"];
  $strFind = $row["find"];
  $strRate = $row["rate"];
  $strComments = $row["comments"];
  $datDate = $row["gbdate"];

}
include "../includes/header.php";
?>
<div id="main" class="container">
  <header>
    <h2>Edit Entry</h2>
  </header>
  <?php

  $sql = "SELECT * FROM " . DBPREFIX . "fields";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    ?>
    <form action="admin_edit.php?gid=<?php echo $guestbookID; ?>" method="post">
      <input type="hidden" name="edit" value="yes" />
      <div class="row">
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <span>
            <strong>Date:</strong>
            <?php echo $datDate; ?>
          </span>
        </div>
        <?php
        while ($row = $result->fetch_assoc()) {
          if ($row["field_show"] == "yes") {
            if ($row["field_name"] == "Site Visited") {
              ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="site" style="margin-bottom:-3px;">Site Visited:</label>
          <div class="select-wrapper">
            <?php selectSite($strSite); ?>
          </div>
        </div>
              <?php
            } else if ($row["field_name"] == "Site Rating") {
              ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="rate" style="margin-bottom:-3px;">Rate My Site</label>
          <div class="select-wrapper">
          <?php selectRate($strRate); ?>
          </div>
        </div>
         <?php
            } else if ($row["field_name"] == "Find Us?") {
         ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="find" style="margin-bottom:-3px;">How did you find us?</label>
          <div class="select-wrapper">
          <?php selectFind($strFind); ?>
          </div>
        </div>
        <?php
            } else if ($row["field_name"] == "Comments") {
        ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="comments" style="margin-bottom:-3px;">Comments:</label>
          <textarea id="comments" name="comments" cols="30" rows="5">
          <?php echo $strComments; ?>
          </textarea>
        </div>
        <?php
            } else if ($row["field_name"] == "Name") {
         ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="name" style="margin-bottom:-3px;">Name:</label>
          <input id="name" name="name" type="text" value="<?php echo $strName; ?>" />
        </div>
        <?php
            } else if ($row["field_name"] == "Email") {
         ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="email" style="margin-bottom:-3px;">Email:</label>
          <input id="email" name="email" type="text" value="<?php echo $strEmail; ?>" />
        </div>
        <?php
            } else if ($row["field_name"] == "Website") {
        ?>
        <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
          <label for="website" style="margin-bottom:-3px;">Website:</label>
          <input id="website" name="website" type="text" value="<?php echo $strWebsite; ?>" />
        </div>
        <?php
            } else if ($row["field_name"] == "facebook") {
        ?>
      <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
        <label for="facebook" style="margin-bottom:-3px;">Facebook:</label>
        <input id="facebook" name="facebook" type="text" value="<?php echo $strFacebook; ?>" />
      </div>
      <?php
            } else if ($row["field_name"] == "twitter") {
       ?>
      <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
        <label for="twitter" style="margin-bottom:-3px;">Twitter:</label>
        <input id="twitter" name="twitter" type="text" value="<?php echo $strTwitter; ?>" />
      </div>
      <?php
            } else if ($row["field_name"] == "Age") {
       ?>
      <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
        <label for="age" style="margin-bottom:-3px;">Age:</label>
        <input id="age" name="age" type="text" value="<?php echo $strAge; ?>" />
      </div>
      <?php
            } else if ($row["field_name"] == "Location") {
       ?>
      <div class="-3u 6u$ 12u(medium)" style="padding-bottom:10px;">
        <label for="loc" style="margin-bottom:-3px;">Location:</label>
        <input id="loc" name="loc" type="text" value="<?php echo $strLoc; ?>" />
      </div>
      <?php
            }
          }
        }
  }
  $conn->close();
  ?>
      <div class="-3u 6u$ 12u(medium)">
        <input class="button" type="submit" value="Edit Entry" />
      </div>
    </div>
  </form>
</div>
<?php include "../includes/footer.php"; ?>