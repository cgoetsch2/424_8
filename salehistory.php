<!DOCTYPE html>

    
<?php 

if(isset($_POST['order_by'])) {
	$queryConcat = "ASC";
} else {
	$queryConcat = "DESC";
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
    width: 90%;
    table-layout: fixed;
    border-collapse: collapse;
    height:100%
  }

  .fixed_header tbody {
    display: block;
    overflow: auto;
    height:90%;
    
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
    width:5%;
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

    <a href="employeeresources.php" class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24">EMPLOYEE<br>RESOURCES</a>

    <a href="purchasehistory.php" class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24 w3-pale-red">TRANSACTION<br>HISTORY</a><!--Current screen-->

    <a href="inventory.php" class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 ">VIEW<br>INVENTORY</a>

    <a href="transaction.php" class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24">NEW<br>TRANSACTION</a>

  </div>
  <!--Navigation bar ENDS-->

  <!--Display transaction history table STARTS-->
  <div class="w3-display-middle" style="width:90%;height:70%;">

    <!--Container for sorting/filter dropdowns STARTS-->
    <div class="w3-display-container w3-black w3-border" style="width:100%;height:13%;">
    <h1 class="w3-display-topmiddle w3-border" style="padding-left:1%;padding-right:1%;;margin-block-start: 1%;">Sales History</h1>

        <!--Sorting date selection-->
        <form class="w3-half" style="margin-block-start: 1.75%;" action="salehistory.php" type="submit" method="POST">

            <label class="w3-text" style="font-size:medium;margin-left:10%"><b>Check to sort by OLD</b></label>
        
            <input class="w3-check" style="margin-left:1.5%;" type="checkbox" name="order_by" value="ASC"><label style="margin-left:1%;">Old</label> 
            <input class="w3-button w3-dark-grey" type="submit" style="margin-left:1.5%;margin-block-end:0.5%;" value="GO!">

        </form>
        
        <!--Switch display-->
        <form class="w3-half w3-center" style="margin-block-start:1.75%;" action="purchasehistory.php" type="submit">
           
            <label class="w3-text" style="margin-left:45%;font-size:medium;"><b>Switch to Purchase View: </b></label>
            <input class="w3-button w3-dark-grey" type="submit" style="margin-left:1.5%;margin-block-end:0.5%;" value="GO!">
            
        </form>
    
    

    </div>
    <!--Container for sorting-filter dropdowns ENDS-->
    
    <table class="fixed_header w3-border w3-striped" style="width:100%;height:95%;"><!--Striped makes rows alternate colors for enhanced readability-->
    <!--Table header STARTS-->
    <thead style="width:100%;">

    <tr style="font-size: small;">
      <th>Sale Date</th>
      <th>Transaction ID</th>
      <th>Sale ID</th>
      <th>Product ID</th>
      <th class="w3-right-align">Quantity Sold</th><!--Aligns header cell ONLY ... does not affect cells in table column-->
      <th class="w3-right-align">Retail Price</th>
    </tr>
    </thead>
    <!--Table header ENDS-->

    <tbody style="width:100%;">
    <!--'tr' encases properties for new row in table ... HTML requires manual entry of each row value
     -Code TBU to query database and display DB information instead-->


<?php
//db login info
$servername = 'localhost';
$username = 'id16456968_group8';
$password = 'Nr3hPeniS7\^@k2a$';
$dbname = 'id16456968_is424_group8_db';
$conn = new mysqli($servername, $username, $password, $dbname);
//testing db login
if (!$conn) {
	die("failed to connect to db");
}

//INNER JOIN returns records where matches are found in BOTH tables (should always be the case if transaction.php works correctly, as it was set up to only product complete records in transaction and, inventory, and supplier_purchase/sale
$saleQuery = "SELECT * FROM sale INNER JOIN transaction ON sale.transaction_id=transaction.transaction_id ORDER BY sale.transaction_id $queryConcat";
$result = $conn->query($saleQuery);
if(!empty($result) && $result->num_rows > 0) {
	//iteratively display rows of table by echoing <td> statements followed by indexes to $row['value']
	while ($row = $result->fetch_assoc()) {
		echo '<tr class="w3-hover-pale-red">';
		echo '<td>';
		echo $row['transaction_datetime'];
		echo '</td>';
		echo '<td>';
		echo $row['transaction_id'];
		echo '</td>';
		echo '<td>';
		echo $row['sale_id'];
		echo '</td>';
		echo '<td>';
		echo $row['product_id'];
		echo '</td>';
		echo '<td class="w3-right-aligh">';
		echo $row['quantity_sold'];
		echo '</td>';
		echo '<td class="w3-right-align">';
		echo $row['retail_price'];
		echo '</td>';
		echo '</tr>';
		
	}
} else {
	die($saleQuery);
}

	

	

		
?>


    <!--End of properties for ONE row-->
    </tbody>
  </table>
  </div>
  <!--Display transaction history table ENDS-->


  <!--Page footer STARTS-->
  <img class="w3-display-bottommiddle" src="images/PageFooter.png" style="width:98%;margin-bottom:0.5%">
  <!--Page footer ENDS-->

</body>

</html>