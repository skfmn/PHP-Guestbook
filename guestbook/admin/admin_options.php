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

include "../includes/header.php";
?>
<div id="main" class="container">
  <header>
    <h2>Manage Options</h2>
  </header>
  <div class="row">
    <div class="6u">
      <div class="box">
        <header>
          <h3>Site Visited</h3>
        </header>
        <div class="row">
          <div class="12u$">
            <?php
            $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

            if (!$conn) {

              die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM " . DBPREFIX . "site";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              echo "<form action=\"admin_process.php\" method=\"post\">\n";
              echo "<div class=\"row\">\n";
              while ($row = $result->fetch_assoc()) {

                $siteID = $row["siteID"];
                $siteName = $row["site_name"];

                echo "  <div class=\"12u\$\">\n";
                echo "    <input type=\"checkbox\" id=\"" . $siteName . "\" name=\"del[]\" value=\"" . $siteID . "\" />\n";
                echo "    <label for=\"" . $siteName . "\">" . $siteName . "</label>\n";
                echo "  </div>\n";
              }
              echo "  <div class=\"12u\$\">\n";
              echo "    <input type=\"submit\" name=\"delsite\" value=\"Delete Selected\">\n";
              echo "  </div>\n";
              echo "</div>\n";
              echo "</form>\n";
            } else {
              echo "0 results";
            }
            $conn->close();
            ?>
          </div>
          <div class="12u$">
            <?php
            echo "<form action=\"admin_process.php\" method=\"post\">\n";
            echo "<div class=\"row\">\n";
            echo "	<div class=\"12u 12u(medium)\" style=\"padding-bottom:10px;\">\n";
            echo "    <input type=\"text\" id=\"newoption\" name=\"newoption\" size=\"20\">\n";
            echo "  </div>";
            echo "  <div class=\"12u 12u(medium)\" style=\"text-align:center;\">\n";
            echo "    <input type=\"submit\" name=\"addsiteoption\" value=\"Add New Option\">\n";
            echo "  </div>\n";
            echo "</div>\n";
            echo "</form>\n";
            ?>
          </div>
        </div>
      </div>

      <div class="box">
        <header>
          <h4>How did you find us?</h4>
        </header>
        <div class="row">
          <div class="12u$">
            <?php

            $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

            if (!$conn) {

              die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM " . DBPREFIX . "find";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              echo "<form action=\"admin_process.php\" method=\"post\">\n";
              echo "<input type=\"hidden\" name=\"find\" value=\"yes\">\n";
              echo "<div class=\"row\">\n";
              while ($row = $result->fetch_assoc()) {
                echo "  <div class=\"12u$ 12u(medium)\">\n";
                echo "    <input type=\"checkbox\" id=\"" . $row["find_name"] . "\" name=\"del\" value=\"" . $row["findID"] . "\">\n";
                echo "    <label for=\"" . $row["find_name"] . "\">" . $row["find_name"] . "</label>\n";
                echo "  </div>\n";
              }
              echo "  <div class=\"12u$ 12u(medium)\">\n";
              echo "    <input type=\"submit\" name=\"delfind\" value=\"Delete Selected\">\n";
              echo "  </div>\n";
              echo "</div>\n";
              echo "</form>\n";
            } else {
              echo "0 results";
            }
            $conn->close();
            ?>
          </div>
          <div class="12u$">
            <?php
            echo "<form action=\"admin_process.php\" method=\"post\">\n";
            echo "<div class=\"row\">\n";
            echo "	<div class=\"12u 12u(medium)\" style=\"padding-bottom:10px;\">\n";
            echo "    <input type=\"text\" id=\"newoption\" name=\"newoption\" size=\"20\">\n";
            echo "  </div>\n";
            echo "  <div class=\"12u 12u(medium)\" style=\"text-align:center;\">\n";
            echo "    <input type=\"submit\" name=\"addfindoption\" value=\"Add New Option\">\n";
            echo "  </div>\n";
            echo "</div>\n";
            echo "</form>\n";
            ?>
          </div>
        </div>
      </div>

    </div>
    <div class="6u$">
      <div class="box">
        <header>
          <h4>Guestbook Fields</h4>
        </header>
        <div class="row">
          <div class="12u$">
            <?php

            $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

            if (!$conn) {

              die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM " . DBPREFIX . "fields";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              echo "<form action=\"admin_process.php\" method=\"post\">\n";
              echo "<div class=\"row\">\n";
              while ($row = $result->fetch_assoc()) {

                $checked = "";
                $fieldID = 0;
                $fieldName = "";
                $fieldShow = "";

                $fieldID = $row["fieldID"];
                $fieldName = $row["field_name"];
                $fieldShow = $row["field_show"];
                if ($fieldShow == "yes") {
                  $checked = "checked";
                }

                echo "  <div class=\"5u\">\n";
                echo "    <input type=\"checkbox\" id=\"" . $fieldName . "\" name=\"show[]\" value=\"" . $fieldID . "\" " . $checked . " >\n";
                echo "    <label for=\"" . $fieldName . "\">" . $fieldName . "</label>\n";
                echo "  </div>\n";
                echo "  <div class=\"4u\$\">";
                if ($fieldShow == "yes") {
                  echo "Showing";
                } else {
                  echo "Hidden";
                }
                echo "  </div>\n";
              }
              echo "	<div class=\"12u 12u(medium)\">\n";
              echo "    <input type=\"submit\" name=\"checkfields\" value=\"check fields\">\n";
              echo "  </div>\n";
              echo "</div>\n";
              echo "</form>\n";
            } else {
              echo "0 results";
            }
            $conn->close();

            $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

            if (!$conn) {

              die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM " . DBPREFIX . "options";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {

              while ($row = $result->fetch_assoc()) {
                $orderBy = $row["orderby"];
                $entriesPage = $row["entries_page"];
                $comCount = $row["com_count"];
              }

            } else {
              echo "0 results";
            }
            $conn->close();

            $checked = "";
            if ($orderBy == "desc") {
              $checked = "checked";
            }
            ?>
          </div>
        </div>
      </div>

      <div class="box">
        <header>
          <h4>More Options</h4>
        </header>
        <div class="row">
          <div class="12u$">
            <form action="admin_process.php" method="post">
              <div class="row">
                <div class="12u 12u(medium)">
                  <input type="checkbox" id="orderby" name="orderby" <?php echo $checked; ?> />
                  <label for="orderby">Show newest entry first?</label>
                </div>
                <div class="6u$ 12u(medium)">
                  <input type="text" id="entriespage" name="entriespage" value="<?php echo $entriesPage; ?>" size="3" />
                  <label for="entriespage">Number of entries to display per page</label>
                </div>
                <div class="6u$ 12u(medium)">
                  <input type="text" id="comcount" name="comcount" value="<?php echo $comCount; ?>" size="3" />
                  <label for="comcount">Number of characters to allow in comment box</label>
                </div>
                <div class="6u$ 12u(medium)">
                  <input type="submit" name="moreoptions" value="Submit" />
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<?php include "../includes/footer.php" ?>