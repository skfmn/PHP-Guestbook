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

$strIP = "";
$blnBanned = false;
$guestookID = 0;

if (isset($_GET["gid"])) {
    $guestookID = test_input($_GET["gid"]);
}

$sql = "SELECT IP FROM " . DBPREFIX . "guestbook WHERE guestbookID = " . $guestookID;
$result = $conn->query($sql);

$param1 = $guestookID;
$stmt = $conn->prepare("SELECT IP FROM " . DBPREFIX . "guestbook WHERE guestbookID = ?");
$stmt->bind_param("s", $param1);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $strIP = $row["IP"];
}

$sql = "SELECT IP FROM " . DBPREFIX . "IP WHERE IP = '" . $strIP . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $blnBanned = true;
}

if ($blnBanned) {

    $param1 = $strIP;
    $stmt = $conn->prepare("DELETE FROM " . DBPREFIX . "IP WHERE IP = ?");
    $stmt->bind_param("s", $param1);
    $stmt->execute();

    $_SESSION["msg"] = "unban";
    redirect($redirect . "admin/admin_entries.php");
    ob_end_flush();

} else {

    $param1 = $strIP;
    $stmt = $conn->prepare("INSERT INTO " . DBPREFIX . "IP (IP) VALUES (?)");
    $stmt->bind_param("s", $param1);
    $stmt->execute();

    $_SESSION["msg"] = "ban";
    redirect($redirect . "admin/admin_entries.php");
    ob_end_flush();

}
$conn->close();
?>