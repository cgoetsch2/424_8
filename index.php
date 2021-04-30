<!DOCTYPE html>

<?php
//db login info
$servername = 'localhost';
$username = 'id16456968_group8';
$password = 'Nr3hPeniS7\^@k2a$';
$dbname = 'id16456968_is424_group8_db';
$conn = new mysqli($servername, $username, $password, $dbname);
//testing db login

if (!$conn) {
	die('failed to connect to db');
}
?>
  
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/w3-theme-black.css">

<body>

    <!--Login page header design STARTS-->

    <img class="w3-display-topleft" src="images/HSTopLeftImage.png" class="w3-rounded-small" style="width:17%">
    <img class="w3-display-topmiddle" src="images/BeStylishNEWLOGO.png" class="w3-rounded-small" style="width:17%">
    <img class="w3-display-topright" src="images/HSTopRightImage.png" class="w3-rounded-small" style="width:17%">
    <!--Login page header design ENDS-->
    <!-- Middle page decoration STARTS-->
    <img class="w3-display-middle" style="width:73%;padding-bottom:10%;margin-top:1%;margin-bottom:15%"
        src="images/HSCenterImage.png" class="w3-rounded-small">
    <!--Middle page decoration ENDS-->
    <!--Input for Employee ID and Submit button starts-->
    <form class="w3-display-middle" action="homepage.php" style="width:35%;margin-block-start: 12.5%;" method="POST">
        <input class="w3-left w3-input w3-border w3-large" style="width:60%" type="text" id="employeeCode"
            name="employeeCode" placeholder="Enter Employee ID">
        <input type="submit" class="w3-right w3-button w3-black w3-large" value="Log In">
    </form>
    <!--Input for Employee ID and Submit button ENDS-->
    <!--Page footer STARTS-->
    <img class="w3-display-bottomleft" src="images/HSBottomLeftImage.png" class="w3-rounded-small" style="width:17%">
    <img class="w3-display-bottomright" src="images/HSBottomRightImage.png" class="w3-rounded-small" style="width:17%">
    <!--Page footer ENDS-->
</body>
</html>
