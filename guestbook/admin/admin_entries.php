<?php
session_start();
ob_start();
include "../includes/functions.php";
include "../includes/globals.php";

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

$total_pages = $conn->query("SELECT * FROM " . DBPREFIX . "guestbook")->num_rows;

include "../includes/header.php";
?>
<div id="main" class="container">
  <header>
    <h2>Manage Entries</h2>
  </header>
  <div class="row">
    <?php
    if ($total_pages != 0) {
      $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? $_GET["page"] : 1;

      $sql = "SELECT entries_page FROM " . DBPREFIX . "options";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $num_results_on_page = $row["entries_page"];
      } else {
        $num_results_on_page = 5;
      }

      if ($stmt = $conn->prepare("SELECT * FROM " . DBPREFIX . "guestbook ORDER BY guestbookID desc LIMIT ?,?")) {

        $calc_page = ($page - 1) * $num_results_on_page;

        if ($total_pages < $num_results_on_page) {
          $num_results_on_page = $total_pages;
        }

        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

          $blnBanned = false;

          $sql = "SELECT * FROM " . DBPREFIX . "IP WHERE IP = '" . $row["IP"] . "'";
          $resultA = $conn->query($sql);
          if ($resultA->num_rows > 0) {
            $blnBanned = true;
          }
          ?>
          <div class="-3u 6u 12u(medium)">
            <div class="table-wrapper">
              <table class="alt" style="border:#dddddd solid 1px;">
                <tbody>
                  <?php
                  $sql = "SELECT * FROM " . DBPREFIX . "fields";
                  $resultA = $conn->query($sql);
                  if ($resultA->num_rows > 0) {
                    ?>
                    <tr>
                      <td style="text-align:left;width:30%;">
                        <strong>Date:</strong>
                      </td>
                      <td style="text-align:left;width:70%;">
                        <?php echo $row["gbdate"]; ?>
                      </td>
                    </tr>
                    <?php
                    while ($row2 = $resultA->fetch_assoc()) {
                      if ($row2["field_show"] == "yes") {
                        if ($row2["field_name"] == "Site Visited") {
                          ?>
                          <tr>
                            <td style="text-align:left;width:30%;">
                              <strong>Site Visited:</strong>
                            </td>
                            <td style="text-align:left;width:70%;">
                              <?php echo $row["site"]; ?>&nbsp;
                            </td>
                          </tr>
                        <?php } else if ($row2["field_name"] == "Site Rating") { ?>
                            <tr>
                              <td style="text-align:left;width:30%;">
                                <strong>Site Rating:</strong>
                              </td>
                              <td style="text-align:left;width:70%;">
                              <?php echo $row["rate"]; ?>&nbsp;
                              </td>
                            </tr>
                          <?php } else if ($row2["field_name"] == "Find Us?") { ?>
                              <tr>
                                <td style="text-align:left;width:30%;">
                                  <strong>How did you find us?</strong>
                                </td>
                                <td style="text-align:left;width:70%;">
                                <?php echo $row["find"]; ?>&nbsp;
                                </td>
                              </tr>
                            <?php } else if ($row2["field_name"] == "Comments") { ?>
                                <tr>
                                  <td style="text-align:left;width:30%;">
                                    <strong>Comments:</strong>
                                  </td>
                                  <td style="text-align:left;width:70%;">
                                  <?php echo $row["comments"]; ?>&nbsp;
                                  </td>
                                </tr>
                              <?php } else if ($row2["field_name"] == "Name") { ?>
                                  <tr>
                                    <td style="text-align:left;width:30%;">
                                      <strong>Name:</strong>
                                    </td>
                                    <td style="text-align:left;width:70%;">
                                    <?php echo $row["name"]; ?>&nbsp;
                                    </td>
                                  </tr>
                                <?php } else if ($row2["field_name"] == "Email") { ?>
                                    <tr>
                                      <td style="text-align:left;width:30%;">
                                        <strong>Email:</strong>
                                      </td>
                                      <td style="text-align:left;width:70%;">
                                        <a href="mailto:<?php echo $row["email"]; ?>">
                                        <?php echo $row["email"]; ?>
                                        </a>
                                        &nbsp;
                                      </td>
                                    </tr>
                                  <?php } else if ($row2["field_name"] == "Website") { ?>
                                      <tr>
                                        <td style="text-align:left;width:30%;">
                                          <strong>Website:</strong>
                                        </td>
                                        <td style="text-align:left;width:70%;">
                                          <a href="http://<?php echo $row["website"]; ?>">
                                          <?php echo $row["website"]; ?>
                                          </a>
                                          &nbsp;
                                        </td>
                                      </tr>
                                    <?php } else if ($row2["field_name"] == "facebook") { ?>
                                        <tr>
                                          <td style="text-align:left;width:30%;">
                                            <strong>Facebook:</strong>
                                          </td>
                                          <td style="text-align:left;width:70%;">
                                          <?php echo $row["facebook"]; ?>&nbsp;
                                          </td>
                                        </tr>
                                      <?php } else if ($row2["field_name"] == "twitter") { ?>
                                          <tr>
                                            <td style="text-align:left;width:30%;">
                                              <strong>Twitter:</strong>
                                            </td>
                                            <td style="text-align:left;width:70%;">
                                            <?php echo $row["twitter"]; ?>&nbsp;
                                            </td>
                                          </tr>
                                        <?php } else if ($row2["field_name"] == "Age") { ?>
                                            <tr>
                                              <td style="text-align:left;width:30%;">
                                                <strong>Age:</strong>
                                              </td>
                                              <td style="text-align:left;width:70%;">
                                              <?php echo $row["age"]; ?>&nbsp;
                                              </td>
                                            </tr>
                                          <?php } else if ($row2["field_name"] == "Location") { ?>
                                              <tr>
                                                <td style="text-align:left;width:30%;">
                                                  <strong>Location:</strong>
                                                </td>
                                                <td style="text-align:left;width:70%;">
                                                <?php echo $row["loc"]; ?>&nbsp;
                                                </td>
                                              </tr>
                                            <?php }
                      }
                    }
                  }

                  $banned = "";
                  if ($blnBanned) {
                    $banned = "<span style=\"color:#ff0000\">IP Banned</span>";
                  }
                  ?>
                  <tr>
                    <td style="text-align:left;width:30%;">
                      <strong>IP:</strong>
                    </td>
                    <td style="text-align:left;width:70%;">
                      <?php echo $row["IP"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $banned; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" style="margin:0px;padding:0px;border:0px;text-align:center;">
                      <table style="margin:0px;padding:0px;border:0px;">
                        <tbody>
                          <tr>
                            <td>
                              <a class="button" onclick="return confirmSubmit('Are you sure you want to delete this entry?','admin_delete.php?gid=<?php echo $row["guestbookID"]; ?>')">
                                <i class="fa fa-times-circle"></i>
                                Delete
                              </a>
                            </td>
                            <td>
                              <a class="button" href="admin_edit.php?gid=<?php echo $row["guestbookID"]; ?>">
                                <i class="fa fa-edit"></i>
                                Edit
                              </a>
                            </td>
                            <td>
                              <?php if ($blnBanned) { ?>
                                <a class="button" href="admin_banip.php?gid=<?php echo $row["guestbookID"]; ?>">
                                  <i class="fa fa-circle-o"></i>
                                  Un-ban
                                </a>
                              <?php } else { ?>
                                <a class="button" href="admin_banip.php?gid=<?php echo $row["guestbookID"]; ?>">
                                  <i class="fa fa-ban"></i>
                                  Ban
                                </a>
                              <?php } ?>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <?php
        }
      }
    } else {
      ?>
    <div class="-3u 6u 12u(medium)">No Entries</div>
    <?php

    }
    ?>
  </div>
</div>
<?php
if ($total_pages != 0) {
  ?>
  <div id="main" class="container">
    <div class="row">
      <div class="-3u 6u 12u(medium)">
        <?php if (ceil($total_pages / $num_results_on_page) > 0) { ?>
          <ul class="pagination">
            <?php if ($page > 1) { ?>
              <li class="prev">
                <a href="admin_entries.php?page=<?php echo $page - 1; ?>">Prev</a>
              </li>
            <?php } ?>

            <?php if ($page > 3) { ?>
              <li class="start">
                <a href="admin_entries.php?page=1">1</a>
              </li>
              <li class="dots">...</li>
            <?php } ?>

            <?php if ($page - 2 > 0) { ?><li class="page">
              <a href="admin_entries.php?page=<?php echo $page - 2; ?>">
                <?php echo $page - 2; ?>
              </a>
            </li>
          <?php } ?>
            <?php if ($page - 1 > 0) { ?><li class="page">
              <a href="admin_entries.php?page=<?php echo $page - 1; ?>">
                <?php echo $page - 1; ?>
              </a>
            </li>
          <?php } ?>

            <li class="currentpage">
              <a href="admin_entries.php?page=<?php echo $page; ?>">
                <?php echo $page; ?>
              </a>
            </li>

            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1) { ?><li class="page">
              <a href="admin_entries.php?page=<?php echo $page + 1; ?>">
                <?php echo $page + 1; ?>
              </a>
            </li>
          <?php } ?>
            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1) { ?><li class="page">
              <a href="admin_entries.php?page=<?php echo $page + 2; ?>">
                <?php echo $page + 2; ?>
              </a>
            </li>
          <?php } ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2) { ?>
              <li class="dots">...</li>
              <li class="end">
                <a href="admin_entries.php?page=<?php echo ceil($total_pages / $num_results_on_page); ?>">
                  <?php echo ceil($total_pages / $num_results_on_page); ?>
                </a>
              </li>
            <?php } ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)) { ?>
              <li class="next">
                <a href="admin_entries.php?page=<?php echo $page + 1; ?>">Next</a>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php
}
$conn->close();
include "../includes/footer.php";
?>