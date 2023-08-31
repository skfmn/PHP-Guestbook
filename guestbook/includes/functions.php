<?php

$msg = "";
if (isset($_GET['msg'])) {
    $msg = test_input($_GET["msg"]);
}

function selectFind($sFind)
{

    $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM " . DBPREFIX . "find";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<select id=\"find\" name=\"find\" style=\"color:#676767;\">\n";
        echo "  <option value=\"\">How did you find us?</option>\n";
        while ($row = $result->fetch_assoc()) {
            $find = $row["find_name"];
            if ($sFind == $find) {
                echo "<option value=\"" . $find . "\" selected=\"selected\">" . $find . "</option>\n";
            } else {
                echo "<option value=\"" . $find . "\">" . $find . "</option>\n";
            }
        }
        echo "</select>\n";
    } else {
        echo "<select><option>No Options</option></select>\n";
    }
    $conn->close();
}

function selectRate($iRate)
{

    echo "<select id=\"rate\" name=\"rate\" style=\"color:#676767;\">\n";
    echo "  <option value=\"\">Rate Our Site</option>\n";
    for ($x = 10; $x >= 1; $x--) {
        if ($iRate == $x) {
            echo "  <option value=\"" . $x . "\" selected>" . $x . "</option>\n";
        } else {
            echo "  <option value=\"" . $x . "\">" . $x . "</option>\n";
        }
    }
    echo "</select>\n";
}

function selectSite($sSite)
{
    $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM " . DBPREFIX . "site";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<select id=\"site\" name=\"site\" style=\"color:#676767;\">\n";
        echo "<option value=\"\">Site Visited</option>\n";
        while ($row = $result->fetch_assoc()) {
            $siteName = "";
            $siteName = $row["site_name"];
            if ($sSite == $siteName) {
                echo "<option value=\"" . $siteName . "\" selected=\"selected\">" . $siteName . "</option>\n";
            } else {
                echo "<option value=\"" . $siteName . "\">" . $siteName . "</option>\n";
            }
        }
        echo "</select>\n";
    } else {
        echo "<select><option>No Options</option></select>\n";
    }
    $conn->close();
}

function randChrs($num)
{

    $sWord = $rchr = "";
    for ($x = 0; $x <= $num; $x++) {
        $rchr = chr(rand(27, 126));
        $pattern = "/[a-zA-Z0-9 , @$#%]/";

        if (preg_match($pattern, $rchr)) {
            $sWord = $sWord . $rchr;
        }
    }
    return $sWord;
}

function deleteDir($path)
{

    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file) {
            deleteDir(realpath($path) . '/' . $file);
        }

        return rmdir($path);

    } else if (is_file($path) === true) {

        return unlink($path);
    }

    return false;
}

function getMessage($sMsg)
{

    $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());
    }

    $strTemp = "";
    $sql = "SELECT message FROM " . DBPREFIX . "messages WHERE msg = '" . trim($sMsg) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $strTemp = $row["message"];
    } else {
        $strTemp = $sMsg;
    }
    $conn->close();

    return $strTemp;

}

function displayFancyMsg($sText)
{
    ?>
    <div style="display:none">
        <a id="textmsg" href="#displaymsg">Message</a>
        <div id="displaymsg" style="background-color:#fff;text-align:left;min-width:300px;padding:5px;">
            <div class="left_menu_block">
                <div class="left_menu_top">
                    <h2>Message</h2>
                </div>
                <div class="left_menu_center" align="center" style="background-color:#fff; padding-left:0px;">
                    <span style="color:#444;">
                        <?php echo $sText; ?>
                    </span>
                </div>
                <div class="left_menu_bottom"></div>
            </div>
        </div>
    </div>
    <?php
}

function msgTrans($sMsg)
{
    $strtmp = "";
    switch ($sMsg) {
        case "lic":
            $strtmp = "Change login info:";
            break;
        case "fch":
            $strtmp = "Fields changed:";
            break;
        case "opa":
            $strtmp = "Options changed:";
            break;
        case "dls":
            $strtmp = "Site deleted:";
            break;
        case "dlf":
            $strtmp = "Find deleted:";
            break;
        case "msgd":
            $strtmp = "Message deleted:";
            break;
        case "ipd":
            $strtmp = "IP deleted:";
            break;
        case "ban":
            $strtmp = "Ban IP:";
            break;
        case "unban":
            $strtmp = "Unban IP:";
            break;
        case "sus":
            $strtmp = "Thank you:";
            break;
        case "ed":
            $strtmp = "Entry deleted:";
            break;
        case "aeo":
            $strtmp = "Operation incomplete:";
            break;
        case "ened":
            $strtmp = "Entry edited:";
            break;
        case "unamenf":
            $strtmp = "Name not found:";
            break;
        case "wpwd":
            $strtmp = "Wrong password:";
            break;
        case "mus":
            $strtmp = "Message updated:";
            break;
        case "error":
            $strtmp = "Error message:";
            break;
        case "adad":
            $strtmp = "Admin added:";
            break;
         case "das":
            $strtmp = "Admin deleted:";
            break;
        case "ant":
            $strtmp = "Admin taken:";
            break;
        case "cpwds":
            $strtmp = "Changed Admins Password:";
            break;
        case "nadmin":
            $strtmp = "No change Admin info:";
            break;
        default:
            $strtmp = "If you see this you messed with the code!";
    }

    return $strtmp;
}

function redirect($location)
{
    if ($location) {

        header('Location: ' . $location);
        exit;

    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function test_inputA($data)
{
    $data = trim($data);
    //$data = str_replace("\\","/",$str);
    $data = htmlspecialchars($data);
    return $data;
}

function getNumtxt($sNum)
{

    $strTemp = "";

    switch ($sNum) {
        case 1:
            $strTemp = "11.png";
            break;
        case 2:
            $strTemp = "12.png";
            break;
        case 3:
            $strTemp = "13.png";
            break;
        case 4:
            $strTemp = "14.png";
            break;
        case 5:
            $strTemp = "15.png";
            break;
        case 6:
            $strTemp = "16.png";
            break;
        case 7:
            $strTemp = "17.png";
            break;
        case 8:
            $strTemp = "18.png";
            break;
        case 9:
            $strTemp = "19.png";
            break;
        default:
            $strTemp = "20.png";
    }

    return $strTemp;

}

?>