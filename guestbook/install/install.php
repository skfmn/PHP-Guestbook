<?php
ob_start();
include '../includes/functions.php';

$step = "";
$step = isset($_GET["step"]) ? $_GET['step'] : "";

$servname = $username = $dbpassword = $dbname = $dbprefix = $basedir = $gbdir = "";
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>PHPGuestbook Installation</title>
    <link type="text/css" rel="stylesheet" href="../assets/css/main.css" />
</head>
<body>
    <div id="main" class="container" style="margin-top:-75px;text-align:center;">
        <div class="row 50%">
            <div class="12u 12u$(medium)">
                <header>
                    <h2>PHPGuestbook Installation</h2>
                </header>
            </div>
        </div>
    </div>
    <?php if ($step == "one") { ?>
        <div id="main" class="container" style="margin-top:-100px;text-align:center;">
            <div class="row 50%">
                <div class="12u 12u$(medium)">
                    <form action="install.php?step=two" method="post">
                        <header>
                            <h2>MySQL Database</h2>
                        </header>
                        <div class="row">
                            <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                <label for="servername" style="text-align:left;">
                                    Server Host Name or IP Address
                                    <input type="text" name="servername" required />
                                </label>
                            </div>
                            <div class="4u 1u$">
                                <span></span>
                            </div>

                            <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                <label for="dbname" style="text-align:left;">
                                    Database Name
                                    <input type="text" name="dbname" required />
                                </label>
                            </div>
                            <div class="4u 1u$">
                                <span></span>
                            </div>

                            <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                <label for="username" style="text-align:left;">
                                    Database Login
                                    <input type="text" name="username" required />
                                </label>
                            </div>
                            <div class="4u 1u$">
                                <span></span>
                            </div>

                            <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                <label for="dbpassword" style="text-align:left;">
                                    Database Password
                                    <input type="password" name="dbpassword" required />
                                </label>
                            </div>
                            <div class="4u 1u$">
                                <span></span>
                            </div>

                            <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                <label for="dbprefix" style="text-align:left;">
                                    Table Prefix
                                    <input type="text" name="dbprefix" value="gb_" required />
                                </label>
                            </div>
                            <div class="4u 1u$">
                                <span></span>
                            </div>

                            <div class="12u 12u$(medium)">
                                <input class="button" type="submit" name="submit" value="Continue" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else if ($step == "two") {
        ?>
            <div id="main" class="container" style="text-align:center;">
                <div class="row 50%">
                    <div class="12u 12u$(medium)">
                        <?php

                        $servername = test_input($_POST["servername"]);
                        $dbname = test_input($_POST["dbname"]);
                        $username = test_input($_POST["username"]);
                        $dbpassword = test_input($_POST["dbpassword"]);
                        $dbprefix = test_input($_POST["dbprefix"]);

                        $conn = mysqli_connect($servername, $username, $dbpassword, $dbname);

                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        echo "Creating Database Tables<br /><br />";

                        echo "Creating Admin table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "admin (
            adminID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          name NVARCHAR(255) NOT NULL ,
	          pwd NVARCHAR(255) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Admin table created successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating admin table...<br />";

                        $tempPassword = "";
                        $tempPassword = password_hash("admin", PASSWORD_DEFAULT);

                        $param2 = "admin";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "admin (name,pwd) VALUES (?,?)");
                        $stmt->bind_param('ss', $param2, $tempPassword);

                        if ($stmt->execute()) {
                            echo "Admin table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating settings table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "settings (
            settingID INT NOT NULL AUTO_INCREMENT PRIMARY KEY  ,
	          site_title VARCHAR(255) NOT NULL ,
	          domain_name VARCHAR(255) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Settings created successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating Messages table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "messages (
	          messageID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          msg VARCHAR(50) NOT NULL ,
            message VARCHAR(50) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Messages table created successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating Messages table...<br />";

                        $param1 = "lic";
                        $param2 = "Your Login Info Has Been Changed!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "fch";
                        $param2 = "Fields have been changed!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "opa";
                        $param2 = "Options have been changed!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "dls";
                        $param2 = "Site Deleted!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "dlf";
                        $param2 = "Find Deleted!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "msgd";
                        $param2 = "Entry Deleted!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "ipd";
                        $param2 = "IP Deleted!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "ban";
                        $param2 = "You have banned the IP address!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "unban";
                        $param2 = "You have un-banned the IP Address!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "sus";
                        $param2 = "Thank you for signing our guestbook!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "ed";
                        $param2 = "Entry Deleted!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "aeo";
                        $param2 = "The operation could not be completed!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "ened";
                        $param2 = "Entry Edited!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "wpwd";
                        $param2 = "Wrong Password!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "unamenf";
                        $param2 = "User name was not found!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "mus";
                        $param2 = "Messages updated successfully!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "error";
                        $param2 = "An unknown error has occurred.<br />Please contact support.";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "adad";
                        $param2 = "You have successfully added and Admin.";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "das";
                        $param2 = "You have successfully deleted the Admin.";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "ant";
                        $param2 = "Admin name taken.";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "cpwds";
                        $param2 = "You changed the password successfully!";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "nadmin";
                        $param2 = "You can not change this Admins Info.";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "messages (msg,message) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();


                        if ($stmt->execute()) {
                            echo "Messages table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating Fields table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "fields (
	          fieldID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          field_name VARCHAR(50) NOT NULL ,
	          field_show VARCHAR(50) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Fields table populated successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating Fields table...<br />";

                        $param1 = "Name";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Email";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Website";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "facebook";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "twitter";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Age";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Location";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Site Visited";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Site Rating";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Find Us?";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);
                        $stmt->execute();

                        $param1 = "Comments";
                        $param2 = "yes";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "fields (field_name,field_show) VALUES (?,?)");
                        $stmt->bind_param("ss", $param1, $param2);

                        if ($stmt->execute()) {
                            echo "Fields table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating find table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "find (
	          findID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          find_name VARCHAR(50) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Find table created successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating find table...<br />";

                        $param1 = "search engine";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "find (find_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);
                        $stmt->execute();

                        $param1 = "word of mouth";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "find (find_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);
                        $stmt->execute();

                        $param1 = "just surfed in";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "find (find_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);
                        $stmt->execute();

                        $param1 = "you-you idiot";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "find (find_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);
                        $stmt->execute();

                        $param1 = "Hotscripts.com";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "find (find_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);

                        if ($stmt->execute()) {
                            echo "Find table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating guestbook table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "guestbook (
	          guestbookID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          name VARCHAR(50) NOT NULL ,
            email VARCHAR(50) NOT NULL ,
            website VARCHAR(100) NOT NULL ,
	          facebook VARCHAR(255) NOT NULL ,
            twitter VARCHAR(255) NOT NULL ,
            age VARCHAR(50) NOT NULL ,
            loc VARCHAR(50) NOT NULL ,
            site VARCHAR(50) NOT NULL ,
            rate VARCHAR(10) NOT NULL ,
            find VARCHAR(50) NOT NULL ,
            IP VARCHAR(50) NOT NULL ,
            banIP VARCHAR(50) NOT NULL ,
            gbdate VARCHAR(50) ,
            comments VARCHAR(1000) NOT NULL
            )";

                        if ($conn->query($sql)) {
                            echo "Guestbook table created successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating IP table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "IP (
	          IP VARCHAR(50) NOT NULL
            )";

                        if ($conn->query($sql) === TRUE) {
                            echo "IP table created successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating options table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "options (
	          orderby VARCHAR(50) NOT NULL ,
	          entries_page INT(10) ,
            com_count INT(10)
            )";

                        if ($conn->query($sql) === TRUE) {
                            echo "Options table created successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating options table...<br />";

                        $param1 = "desc";
                        $param2 = 5;
                        $param3 = 350;
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "options(orderby,entries_page,com_count) VALUES(?,?,?)");
                        $stmt->bind_param("sii", $param1, $param2, $param3);

                        if ($stmt->execute()) {
                            echo "Options table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating site table...<br />";

                        $sql = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "site (
            siteID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	          site_name VARCHAR(50) NOT NULL
            )";

                        if ($conn->query($sql) === TRUE) {
                            echo "Site table created successfully<br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Populating site table...<br />";

                        $param1 = "HTML Junction";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "site (site_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);
                        $stmt->execute();

                        $param1 = "ASP Junction";
                        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "site (site_name) VALUES (?)");
                        $stmt->bind_param("s", $param1);

                        if ($stmt->execute()) {
                            echo "Site table populated successfully<br /><br />";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        echo "Creating database tables...Complete!<br />";

                        $conn->close();
                        ?>
                        <br />
                        <br />
                    </div>
                </div>
            </div>
            <div id="main" class="container" style="text-align:center;">
                <div class="row 50%">
                    <div class="12u 12u$(medium)">
                        <form action="install.php?step=three" method="post">
                            <input type="hidden" name="servername" value="<?php echo $servername ?>" />
                            <input type="hidden" name="dbname" value="<?php echo $dbname ?>" />
                            <input type="hidden" name="username" value="<?php echo $username ?>" />
                            <input type="hidden" name="dbpassword" value="<?php echo $dbpassword ?>" />
                            <input type="hidden" name="dbprefix" value="<?php echo $dbprefix ?>" />
                            <header>
                                <h3>
                                    <span class="first">
                                        You have successfully installed the MySQL Database<br />
                                        Please click the button below to continue
                                    </span>
                                </h3>
                            </header>
                            <div class="row">
                                <div class="12u 12u$(medium)">
                                    <input class="button" type="submit" name="submit" value="Continue" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
    } else if ($step == "three") {

        $absPath = "";
        $absPath = $_SERVER['DOCUMENT_ROOT'] . "\\";
        ?>
                <div id="main" class="container" style="text-align:center;">
                    <div class="row 50%">
                        <div class="12u 12u$(medium)">
                            <form action="install.php?step=four" method="post">
                                <input type="hidden" name="servername" value="<?php echo test_input($_POST["servername"]) ?>" />
                                <input type="hidden" name="dbname" value="<?php echo test_input($_POST["dbname"]) ?>" />
                                <input type="hidden" name="username" value="<?php echo test_input($_POST["username"]) ?>" />
                                <input type="hidden" name="dbpassword" value="<?php echo test_input($_POST["dbpassword"]) ?>" />
                                <input type="hidden" name="dbprefix" value="<?php echo test_input($_POST["dbprefix"]) ?>" />
                                <header>
                                    <h2>Path Settings</h2>
                                </header>
                                <div class="row">
                                    <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                        <label for="dbid" style="text-align:left;">
                                            Base Directory
                                            <input type="text" name="basedir" value="<?php echo $absPath; ?>" />
                                        </label>
                                    </div>
                                    <div class="4u 1u$">
                                        <span></span>
                                    </div>

                                    <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                        <label for="dir" style="text-align:left;">
                                            Guestbook Directory
                                            <input type="text" name="gbdir" value="/guestbook/" size="40" />
                                        </label>
                                    </div>
                                    <div class="4u 1u$">
                                        <span></span>
                                    </div>
                                    <div class="12u 12u$(medium)">
                                        <input class="button" type="submit" name="submit" value="Continue" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
    } else if ($step == "four") {

        $file = $fileA = "";

        $servername = test_input($_POST["servername"]);
        $username = test_input($_POST["username"]);
        $dbpassword = test_input($_POST["dbpassword"]);
        $dbname = test_input($_POST["dbname"]);
        $dbprefix = test_input($_POST["dbprefix"]);
        $basedir = test_inputA($_POST["basedir"]);
        $gbdir = test_input($_POST["gbdir"]);

        $file = fopen('../includes/globals.php', "r");
        $fileA = fread($file, filesize('../includes/globals.php'));
        fclose($file);

        $basedir = preg_replace("/([\\\])/", '${1}${1}', $basedir);

        $file = fopen('../includes/globals.php', "w");

        $fileA = str_replace("{#servername#}", $servername, $fileA);
        $fileA = str_replace("{#username#}", $username, $fileA);
        $fileA = str_replace("{#dbpassword#}", $dbpassword, $fileA);
        $fileA = str_replace("{#dbname#}", $dbname, $fileA);
        $fileA = str_replace("{#dbprefix#}", $dbprefix, $fileA);
        $fileA = str_replace("{#basedir#}", $basedir, $fileA);
        $fileA = str_replace("{#gbdir#}", $gbdir, $fileA);

        fwrite($file, $fileA);

        fclose($file);
        ?>
                    <div id="main" class="container" style="margin-top:-100px;">
                        <div class="row">
                            <div class="12u 12u$(medium)" style="text-align:center;">
                                <form action="install.php?step=five" method="post">
                                    <input type="hidden" name="servername" value="<?php echo $servername ?>" />
                                    <input type="hidden" name="dbname" value="<?php echo $dbname ?>" />
                                    <input type="hidden" name="username" value="<?php echo $username ?>" />
                                    <input type="hidden" name="dbpassword" value="<?php echo $dbpassword ?>" />
                                    <input type="hidden" name="dbprefix" value="<?php echo $dbprefix ?>" />
                                    <input type="hidden" name="gbdir" value="<?php echo $gbdir ?>" />
                                    <header>
                                        <h2>Other stuff</h2>
                                    </header>
                                    <div class="row">
                                        <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                            <label for="sitetitle" style="text-align:left;">
                                                Site title
                                                <input type="text" name="sitetitle" />
                                            </label>
                                        </div>
                                        <div class="4u 1u$">
                                            <span></span>
                                        </div>

                                        <div class="-4u 4u 12u$(medium)" style="padding-bottom:20px;">
                                            <label for="domainname" style="text-align:left;">
                                                Domain name
                                                <input type="text" name="domainname" value="<?php echo $_SERVER["SERVER_NAME"]; ?>" />
                                            </label>
                                        </div>
                                        <div class="4u 1u$">
                                            <span></span>
                                        </div>
                                        <div class="12u 12u$(medium)">
                                            <input class="button" type="submit" name="submit" value="Continue to next step" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
    } else if ($step == "five") {

        $servername = test_input($_POST["servername"]);
        $dbname = test_input($_POST["dbname"]);
        $username = test_input($_POST["username"]);
        $dbpassword = test_input($_POST["dbpassword"]);
        $dbprefix = test_input($_POST["dbprefix"]);
        $gbdir = test_input($_POST["gbdir"]);

        $conn = mysqli_connect($servername, $username, $dbpassword, $dbname);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $siteTitle = test_input($_POST["sitetitle"]);
        $domainName = test_input($_POST["domainname"]);

        $stmt = $conn->prepare("INSERT INTO " . $dbprefix . "settings (site_title,domain_name) VALUES (?,?)");
        $stmt->bind_param("ss", $siteTitle, $domainName);

        if ($stmt->execute() === TRUE) {

            if ($_SERVER["HTTPS"] == "off") {
                $http = "http";
            } else {
                $http = "https";
            }

            $httpHost = $_SERVER["HTTP_HOST"];
            $redirect = $http . "://" . $domainName . $gbdir;
            redirect($redirect . "install/install.php?step=done");
            ob_end_flush();

        }
        $conn->close();

    } else if ($step == "done") {
        ?>
                            <div id="main" class="container">
                                <div class="row">
                                    <div class="12u 12u$(medium)" style="text-align:center;">
                                        <span class="first">
                                            Success!
                                            <br />
                                            You have successfully configured PHPGuestbook!
                                            <br />
                                            The next step is to change your password.
                                            <br />
                                            Click on the link below and login to admin.
                                            <br />
                                            Click on "Password" in the left options menu and change your password.
                                            <br />
                                            <br />
                                            <a class="first" href="../admin/admin_login.php">Login</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div id="main" class="container" style="margin-top:-75px;">
                                <div class="row">
                                    <div class="-4u 4u$ 12u$(medium)" style="text-align:center;">
                                        <span class="first">
                                            You are about to install PHPGuestbook.
                                            <br />
                                            Please follow the instructions carefully!
                                            <br />
                                            <br />
                                            Before you start:
                                            <ul style="text-align:left;">
                                                <li>Create the MySQL database on your server.</li>
                                                <li>Take note of the Server Name. "localhost" will almost always work, in not contact your provider.</li>
                                                <li>Other examples would be "mysql.example.com" or an IP address.</li>
                                                <li>Also take note of the Database Name, User Name, and Password.</li>
                                                <li>Also make sure you have "write" permissions to the folder.</li>
                                            </ul>
                                            <br />
                                            <br />
                                            <input class="button" type="button" onclick="parent.location='install.php?step=one'" value="Continue" />
                                            <br />
                                            <br />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
    <br />
</body>
</html>