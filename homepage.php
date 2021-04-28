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
$submit = $_POST['employeeCode'];
if(!isset($submit)) { 
	//end session
	die('not set');
} else {
	class Employee {
		private $employeeName;
		private $employeeID;
		private $jobCode;
		
		function setEmployeeName($name) {
			$this->$employeeName = $name;
		}
		function setEmployeeID($id) {
			$this->$employeeID = $id;
		}
		function setJobCode($code) {
			$this->$jobCode = $code;
		}
		function getEmployeeName() {
			return $this->$employeeName;
		}
		function getEmployeeID() { 
			return $this->$employeeID;
		}
		function getJobCode() {
			return $this->$jobCode;
		}
	}
	
	$loginQuery = "SELECT * FROM employee WHERE employee_id ='$submit'";
	$result = $conn->query($loginQuery);
	if (!empty($result) && $result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo 'Welcome '.$row['first_name'].' '.$row['last_name'].'!';
			
		}		
	} else {
		die('Sorry, that login was invalid.');
	}
}

?>

<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=768px, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/w3-theme-black.css">

<body>

  <!--Navigation bar STARS-->

  <div class="w3-bar w3-border-bottom">
    <a href="homepage.php" class="w3-left" style="width:12.25%;">
      <img src="images/NEWnavbarlogo.png" class="w3-rounded-small" style="width:100%;"></a>
    <a href="index.php" class="w3-bar-item w3-button w3-border-left w3-right w3-black w3-padding-24">LOG<br>OUT</a>
  </div>
  <!--Navigation bar ENDS-->
  <!--Container for Enter Transaction Info and View Inventory buttons STARTS-->
  <div class="w3-display-middle" style="width:80%;margin-top:1.5%">
    <!--Navigate to Transaction screen-->
    <a href="transaction.php" class="w3-half w3-button w3-white w3-border"
      style="padding:5%;margin-bottom:2%;width:48%;margin-right:2%;">
      <input type="image" src='images/EnterNewTransactionButton.png' style="width:85%"></a>
    <!--Navigate to Inventory screen-->
    <a href="inventory.php" class="w3-half w3-button w3-white w3-border"
      style="padding:5%;margin-bottom:2%;width:48%;margin-left:2%">
      <input type="image" src='images/ViewInventoryButton.png' style="width:85%"></a>
    <!--Container for Enter Transaction Info and View Inventory buttons ENDS-->
    <!--Container for View Transaction History and Employee Resources buttons STARTS-->
    <!--Navigate to Transaction History screen-->
    <a href="transactionhistory.php" class="w3-half w3-button w3-white w3-border"
      style="padding:5%;width:48%;margin-right:2%">
      <input type="image" src="images/TransactionHistoryButton.png" style="width:85%"></a>
    <!--Navigate to Employee Resources screen-->
    <a href="employeeresources.php" class="w3-half w3-button w3-white w3-border"
      style="padding:5%;width:48%;margin-left:2%">
      <input type="image" src='images/EmployeeResourcesButton.png' style="width:85%"></a>
  </div>
  <!--Container for View Transaction Hustory and Employee Resources buttons ENDS-->
  <!--Page footer STARTS-->
  <img class="w3-display-bottommiddle" src="images/PageFooter.png" style="width:98%;margin-bottom:0.5%">
  <!--Page footer ENDS-->
</body>
</html>