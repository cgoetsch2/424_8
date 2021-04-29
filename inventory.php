<!DOCTYPE html>
<?php
//db .php info
$servername = 'localhost';
$username = 'id16456968_group8';
$password = 'Nr3hPeniS7\^@k2a$';
$dbname = 'id16456968_is424_group8_db';
$conn = new mysqli($servername, $username, $password, $dbname);
$productID = "";
//testing db .php
if (!$conn) {
	die("failed to connect to db");
}
$quantitySold = 0;
$quantityPurchased =0;
$productCode = 0;
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
	

?>

<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/w3-theme-black.css">

<!--CSS for table with FIXED header and SCROLLING body STARTS-->
<style>
  .fixed_header {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    height: 100%
  }
  .fixed_header tbody {
    display: block;
    overflow: auto;
    height: 100%;
  }
  .fixed_header thead tr {
    display: block;
  }
  .fixed_header thead {
    background: black;
    color: #fff;
  }
  .fixed_header th,
  .fixed_header td {
    padding: 0.5%;
    text-align: left;
    width: 3%;
  }
</style>
<!--CSS for table ENDS-->

<body>

  <!--Navigation bar STARTS
    -Button for current screen gets higlighted pale red
    -Clicking logo at left returns user to initial navigation Homepage screen-->
  <div class="w3-bar w3-border-bottom" style="width:100%">
    <a href="homepage.php" class="w3-left" style="width:12.25%;">
      <img src="images/NEWnavbarlogo.png" style="width:100%;"></a>
    <a href="index.php" class="w3-bar-item w3-button w3-border-left w3-right w3-black w3-padding-24">LOG<br>OUT</a>
    <a href="employeeresources.php"
      class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24">EMPLOYEE<br>RESOURCES</a>
    <a href="transactionhistory.php"
      class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24">TRANSACTION<br>HISTORY</a>
    <a href="inventory.php"
      class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 w3-pale-red">VIEW<br>INVENTORY</a>
    <!--Current screen-->
    <a href="transaction.php"
      class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24">NEW<br>TRANSACTION</a>
  </div>
  <!--Navigation bar ENDS-->
  <!--Display current inventory table STARTS-->
  <div class="w3-display-middle" style="width:90%;height:76.5%;margin-top:1.5%">
    <table class="fixed_header w3-striped w3-border">
      <!--Striped class makes row alternate colors for enhanced readability-->
      <!--Table header STARTS-->
      <thead class="w3-black">
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th class="w3-right-align">Quantity In Stock</th>
        </tr>
        <!--Aligns header cell ONLY ... does not affect cells in table column-->
      </thead>
      <!--Table header ENDS-->
      <tbody>
        <!--'tr' encases properties for new row in table ... HTML requires manual entry of each row value
      -Code TBU to query database and display DB information instead-->
          <!--Pale red highlight when row is moused over-->
          <?php 
			$productIDQuery = "SELECT COUNT(product_id) as product_count FROM inventory";
			$result = $conn->query($productIDQuery);
			if(!empty($result) && $result->num_rows > 0) {
					$productCount = $row['product_count'];
			}
			$quantityPurchasedQuery = "SELECT SUM(quantity_purchased) AS sum_qp FROM supplier_purchase WHERE product_id='$productID'"; 
			$result = $conn->query($quantityPurchasedQuery);
			if (!empty($result) && $result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$quantityPurchased = $row['sum_qp'];
				}
			}
			$quantitySoldQuery = "SELECT SUM(quantity_sold) AS sum_qs FROM sale WHERE product_id='$productID'";
			$result = $conn->query($quantitySoldQuery);
			if(!empty($result) && $result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$quantitySold = $row['sum_qs'];
				}
			}
			$productQuery = "SELECT * FROM inventory";
			$result = $conn->query($productQuery);
			//iteratively creates a table and fills it with values from that row of the db
			if (!empty($result) && $result -> num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$productID = $row['product_id'];
					echo '<tr class="w3-hover-pale-red">';
					echo '<td>';
					echo $row['product_id'];
					echo '</td>';
					echo '<td>';
					echo $row['product_name'];
					echo '</td>';
					echo '<td>';
					echo $row['qty_in_stock'];
					echo '</td>';
					echo '</tr>';
						
				}
			} else {
				die('Sorry, we are not able to display the data at this time.'); 
			}
					?>	
        <!--end of properties for ONE row-->
      </tbody>
    </table>
  </div>
  <!--Display current inventory table ENDS-->
  <!--Page footer STARDS-->
  <img class="w3-display-bottommiddle" src="images/PageFooter.png" style="width:98%;margin-bottom:0.5%">
  <!--Page footer ENDS-->
</body>
</html>