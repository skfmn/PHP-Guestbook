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

$guestookID = 0;
if (isset($_GET["gid"])) {
  $guestookID = test_input($_GET["gid"]);
}

$stmt = $conn->prepare("DELETE FROM " . DBPREFIX . "guestbook WHERE guestbookID = ?");
$stmt->bind_param("s", $guestookID);
$stmt->execute();

$conn->close();

$_SESSION["msg"] = "ed";
redirect($redirect . "admin/admin_entries.php");
ob_end_flush();

?>