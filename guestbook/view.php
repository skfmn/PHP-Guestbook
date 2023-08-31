<?php 
  include "includes/functions.php"; 
  include "includes/globals.php";

  if ($msg <> "") {
    displayFancyMsg(getMessage($msg));
  }
 
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
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $sitetitle; ?> | PHPGuestbook - View</title>
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
      <a class="button" href="sign.php"><i class="fa fa-pencil"></i> Sign Guestbook</a>
    </div>
<?php

	$orderBy = "asc";
  $total_pages = $conn->query("SELECT * FROM ".DBPREFIX."guestbook")->num_rows;
  $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? $_GET["page"] : 1;

  $sql = "SELECT * FROM ".DBPREFIX."options";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
		$orderBy = $row["orderby"];
    $num_results_on_page = $row["entries_page"];
  } else {
    $num_results_on_page = 5;
  }

	
  if ($stmt = $conn->prepare("SELECT * FROM ".DBPREFIX."guestbook ORDER BY guestbookID ".$orderBy." LIMIT ?,?")) {

	  $calc_page = ($page - 1) * $num_results_on_page;

    if($total_pages < $num_results_on_page) { $num_results_on_page = $total_pages; }

	  $stmt->bind_param('ii', $calc_page, $num_results_on_page);
	  $stmt->execute(); 

	  $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
?>
    <div class="-3u 6u 12u(medium)">
      <div class="table-wrapper">
			  <table class="alt" style="border:#dddddd solid 1px;">
          <tbody>
	          <tr>
	            <td style="text-align:left;width:30%;"><strong>Date:</strong></td>
	            <td style="text-align:left;width:70%;"><?php echo $row["gbdate"]; ?></td>
	          </tr>		
<?php

      $sql = "SELECT * FROM ".DBPREFIX."fields";
      $resultA = $conn->query($sql);
      if ($resultA->num_rows > 0) {
        while($row2 = $resultA->fetch_assoc()) {
          if ($row2["field_show"] == "yes") {
            if ($row2["field_name"] == "Site Visited") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Site Visited:</strong></td>
			        <td style="text-align:left;"><?php echo $row["site"]; ?></td>
			      </tr>			
<?php		
			      } else if ($row2["field_name"] == "Site Rating") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Site Rating:</strong></td>
			        <td style="text-align:left;"><?php echo $row["rate"]; ?></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Find Us?") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>How did you find us?</strong></td>
			        <td style="text-align:left;"><?php echo $row["find"]; ?></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Comments") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Comments:</strong></td>
			        <td style="text-align:left;"><?php echo $row["comments"]; ?></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Name") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Name:</strong></td>
			        <td style="text-align:left;"><?php echo $row["name"]; ?></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Email") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Email:</strong></td>
					    <td style="text-align:left;"><a href="mailto:<?php echo $row["email"]; ?>"><?php echo $row["email"]; ?></a></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Website") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Website:</strong></td>
			        <td style="text-align:left;"><a href="http://<?php echo $row["website"]; ?>"><?php echo $row["website"]; ?></a></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "facebook") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Facebook:</strong></td>
			        <td style="text-align:left;"><a href="https://www.facebook.com/<?php echo $row["facebook"]; ?>"><?php echo $row["facebook"]; ?></a></td>
			      </tr>				
<?php			
			      } else if ($row2["field_name"] == "twitter") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Twitter:</strong> </td>
			        <td style="text-align:left;"><a href="https://twitter.com/<?php echo $row["twitter"]; ?>"><?php echo $row["twitter"]; ?></a></td>
			      </tr>
<?php			
			      } else if ($row2["field_name"] == "Age") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Age:</strong></td>
			        <td style="text-align:left;"><?php echo $row["age"]; ?></td>
			      </tr>				
<?php			
			      } else if ($row2["field_name"] == "Location") {
?>
			      <tr>
			        <td style="text-align:left;"><strong>Location:</strong></td>
			        <td style="text-align:left;"><?php echo $row["loc"]; ?></td>
			      </tr>											
<?php	
			      }
		      }
	      }
	    }
?>
        </tbody>				
			  </table>
		  </div>
    </div>
<?php
		}
?>
  </div>
</div>   
<?php 
  } 
  $conn->close();
?>
<div id="main" class="container">
  <div class="row">
    <div class="-3u 6u 12u(medium)">
			<?php if (ceil($total_pages / $num_results_on_page) > 0) { ?>
			<ul class="pagination">
				<?php if ($page > 1) { ?>
				<li class="prev"><a href="view.php?page=<?php echo $page-1; ?>">Prev</a></li>
				<?php } ?>

				<?php if ($page > 3) { ?>
				<li class="start"><a href="pagination.php?page=1">1</a></li>
				<li class="dots">...</li>
				<?php } ?>

				<?php if ($page-2 > 0) { ?><li class="page"><a href="view.php?page=<?php echo $page-2; ?>"><?php echo $page-2; ?></a></li><?php } ?>
				<?php if ($page-1 > 0) { ?><li class="page"><a href="view.php?page=<?php echo $page-1; ?>"><?php echo $page-1; ?></a></li><?php } ?>

				<li class="currentpage"><a href="view.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>

				<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1) { ?><li class="page"><a href="view.php?page=<?php echo $page+1; ?>"><?php echo $page+1; ?></a></li><?php } ?>
				<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1) { ?><li class="page"><a href="view.php?page=<?php echo $page+2; ?>"><?php echo $page+2; ?></a></li><?php } ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)-2) { ?>
				<li class="dots">...</li>
				<li class="end"><a href="view.php?page=<?php echo ceil($total_pages / $num_results_on_page); ?>"><?php echo ceil($total_pages / $num_results_on_page); ?></a></li>
				<?php } ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)) { ?>
				<li class="next"><a href="view.php?page=<?php echo $page+1; ?>">Next</a></li>
				<?php } ?>
			</ul>
			<?php } ?>
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
