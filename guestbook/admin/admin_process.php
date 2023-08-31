<?php
session_start();
ob_start();

include '../includes/globals.php';
include '../includes/functions.php';

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

if (isset($_POST["checkfields"])) {

  $sql = "SELECT * FROM " . DBPREFIX . "fields";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $fieldIDs = "";
    $fieldShow = "no";
    $stmt = $conn->prepare("UPDATE " . DBPREFIX . "fields SET field_show = ? ");
    $stmt->bind_param("s", $fieldShow);
    $stmt->execute();

    $fieldIDs = $_POST["show"];
    while ($row = $result->fetch_assoc()) {

      $param1 = $param2 = "";

      $arrlength = count($fieldIDs);
      for ($x = 0; $x < $arrlength; $x++) {

        $param1 = $row["fieldID"];
        $param2 = $fieldIDs[$x];

        if ($param1 == $param2) {

          $fieldShow = "yes";
          $stmt = $conn->prepare("UPDATE " . DBPREFIX . "fields SET field_show = ? WHERE fieldID = ?");
          $stmt->bind_param("ss", $fieldShow, $param2);
          $stmt->execute();

        }
      }
    }

  } else {
    echo "0 results";
  }

  $_SESSION["msg"] = "fch";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

if (isset($_POST["moreoptions"])) {
  $orderBy = "";
  $entriesPage = test_input($_POST["entriespage"]);
  $comCount = test_input($_POST["comcount"]);

  if (isset($_POST["orderby"])) {
    $orderBy = "desc";
  } else {
    $orderBy = "asc";
  }

  $sql = "SELECT * FROM " . DBPREFIX . "options";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {

    $stmt = $conn->prepare("UPDATE " . DBPREFIX . "options SET orderby = ? , entries_page = ?, com_count = ?");
    $stmt->bind_param("sss", $orderBy, $entriesPage, $comCount);
    $stmt->execute();

  } else {
    echo "0 results";
  }

  $_SESSION["msg"] = "opa";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

if (isset($_POST["addsiteoption"])) {

  $param1 = test_input($_POST["newoption"]);
  $stmt = $conn->prepare("INSERT INTO " . DBPREFIX . "site (site_name) VALUES (?)");
  $stmt->bind_param("s", $param1);
  $stmt->execute();

  $_SESSION["msg"] = "opa";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

if (isset($_POST["addfindoption"])) {

  $param1 = test_input($_POST["newoption"]);
  $stmt = $conn->prepare("INSERT INTO " . DBPREFIX . "find (find_name) VALUES (?)");
  $stmt->bind_param("s", $param1);
  $stmt->execute();

  $_SESSION["msg"] = "opa";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

if (isset($_POST["delsite"])) {

  $delsite = $_POST["del"];

  if (!empty($_POST['del'])) {
    foreach ($_POST['del'] as $check) {
      $param1 = $check;
      $stmt = $conn->prepare("DELETE FROM " . DBPREFIX . "site WHERE siteID= ?");
      $stmt->bind_param("s", $param1);
      $stmt->execute();
    }
  }

  $_SESSION["msg"] = "dls";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

if (isset($_POST["delfind"])) {

  $delfind = $_POST["del"];

  $arrlength = count($delfind);
  for ($x = 0; $x < $arrlength; $x++) {

    $param1 = $delfind[$x];
    $stmt = $conn->prepare("DELETE FROM " . DBPREFIX . "find WHERE findID= ?");
    $stmt->bind_param("s", $param1);
    $stmt->execute();

  }

  $_SESSION["msg"] = "dif";
  redirect($redirect . "admin/admin_options.php");
  ob_end_flush();

}

$conn->close();
?>