<?php
  session_start();
  ob_start();
  include '../includes/functions.php';
  include '../includes/globals.php';



  session_unset();
  session_destroy();

  redirect($redirect."admin/admin_login.php");
  ob_end_flush();

?>