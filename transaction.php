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

//creation of global and tracking variables (as we query the database using the user's input, we ensure that all input is valid through these tracking variables
// (this is much cleaner and more readable than making a 4 or 5-times-nested if-statement) 
$isSetPurchase1 = false;
$isSetPurchase2 = false;
$isSetPurchase3 = false;
$isSetPurchase4 = false;
$isSetPurchase5 = false;
$isSetPurchase6 = false;
$isSetSale1 = false;
$isSetSale2 = false;
$isSetSale3 = false;
$isSetSale4 = false;
$descriptionString = "";
$maxID = NULL;
$empID = NULL;
$product_id = 0;
$currDate = date('Y-m-d H:i:s');
$supplierID = NULL;
$saleID = NULL;
$productCode = NULL;
$maxProductCode = NULL;
$transactionID = NULL;
$price = 0;
$cost = 0;
$quantity = 0;


if (isset($_POST['purchase_product_id'])) {
	$isSetPurchase1 = true;
	//identified as SUPPLIER PURCHASE
	//querying transaction table to identify transaction id and increment it
	$transactionIDPurchaseQuery = "SELECT MAX(transaction_id) AS max_id FROM transaction";
	$result = $conn->query($transactionIDPurchaseQuery);
	if (!empty($result) && $result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$transactionID = $row['max_id']+1;
		}
	}
	//querying supplier_transaction table to find max supplier_id
	$supplierIDPurchaseQuery = "SELECT MAX(supplier_id) AS max_id FROM supplier_transaction";
	$result = $conn->query($supplierIDPurchaseQuery);
	if (!empty($result) && $result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$maxSupplierID = $row['max_id']+1;
		}
	}

	$product_id = $_POST['purchase_product_id'];
	if ($product_id != 0) {
		$purchaseProductQuery = "SELECT * FROM inventory WHERE product_id=".$product_id;
		$result = $conn->query($purchaseProductQuery);
		if (!empty($result) && $result -> num_rows > 0) {
			while($row = $result->fetch_assoc()) {			
				$productCode = $row['product_id'];
			}
		} else {
			die('Sorry, that product code was invalid, please try again');
		}
	} else {
		$newProductCodeQuery = "SELECT MAX(product_id) AS max_id FROM inventory";
		$result = $conn->query($newProductCodeQuery);
		if(!empty($result) && $result -> num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$productCode = $row['max_id']+1;
				$maxProductCode = $productCode;
			}
		}
	}
	
	if (isset($_POST['supplier_name'])) {
		$isSetPurchase2 = true;
		$supplierName = strtoupper($_POST['supplier_name']);
		$supplierNameQuery = "SELECT * FROM supplier_transaction WHERE supplier_name=".$supplierName;
		$result = $conn->query($supplierNameQuery);
		if(!empty($result) && $result->num_rows > 0) {
			$supplierID = $row['supplier_id'];
		} else {
			$supplierID = $maxSupplierID;
		}
	} 
	if (isset($_POST['supplier_price'])) {
		$isSetPurchase3 = true;	
		$cost = $_POST['supplier_price'];
	} 
	if(isset($_POST['quantity_purchased'])) {
		$isSetPurchase4 = true;
		$quantity = $_POST['quantity_purchased'];

	}
	if(isset($_POST['purchase_item_description'])) {
		$isSetPurchase5 = true;
		$descriptionString = $_POST['purchase_item_description'];
		
	}
	//verifying employee_id
	if (isset($_POST['purchase_emp_id'])) {
		$empID = $_POST['purchase_emp_id'];
		$employeeQuery = "SELECT * FROM employee WHERE employee_id=".$empID;
		$result = $conn->query($employeeQuery);
		if(!$result) {
			die('Sorry, but that login was invalid, please navigate to the last page and try again');
		} else {
			$isSetPurchase6 = true;
		}
	}
	
	//if all fields in sale have data in them, attempt to use that data to create a new transaction+purchase, as well as a new product if need be
	if ($isSetPurchase1 && $isSetPurchase2 && $isSetPurchase3 && $isSetPurchase4 && $isSetPurchase6) {
		//if product is new, create an entry for it in inventory
		if($isSetPurchase5 && $productCode == $maxProductCode) {	
			$newProductQuery = "INSERT INTO `inventory`(`product_id`, `product_name`, `description`) VALUES ('$productCode', 'placeholder', '$descriptionString')";
			$result = $conn->query($newProductQuery);
			if(!$result) {
				die($newProductQuery);
			}
		}
		//logging new transaction
		$newTransactionQuery = "INSERT INTO `transaction`(`transaction_id`, `transaction_datetime`, `employee_id`) VALUES('$transactionID', '$currDate', '$empID')";
		$result = $conn->query($newTransactionQuery);
		if(!$result) {
			die("Sorry, but we weren't able to log that transaction, please review your input(s)".$transactionID." ".$currDate." ".$emp_id);
			
		} else {
			echo 'transaction success ';
		}
		//logging new purchase
		$newPurchaseQuery = "INSERT INTO `supplier_transaction` (`supplier_id`, `transaction_id`, `product_id`, `supplier_name`, `quantity_purchased`, `cost`) VALUES ('$supplierID', '$transactionID', '$productCode', '$supplierName', '$quantity', '$cost')";
		$result = $conn->query($newPurchaseQuery);
		if(!$result) {
			die("Sorry, but we weren't able to log that purchase, please review your input(s)".$supplierID."_".$transactionID."_".$productCode."_".$supplierName."_".$quantity."_".$cost."_".$conn->error);
		} else {
			echo 'purchase success ';
		}
	}

//end of SUPPLIER PURCHASE
//beginning of RETAIL SALE		
} else if(isset($_POST['sale_product_id'])) {
	//identified as RETAIL SALE
	$transactionIDSaleQuery = "SELECT MAX(transaction_id) AS max_id FROM transaction";
	$result = $conn->query($transactionIDSaleQuery);
	//
	if (!empty($result) && $result->num_rows > 0) {
		//incrementing ID by 1 (enabling auto-incrementing in phpmyadmin isn't possible due to existing foreign key relationships)
		while($row = $result->fetch_assoc()) {
			$transactionID = $row['max_id']+1;
		}
	}
	$saleIDQuery = "SELECT MAX(sale_id) AS max_id FROM sale";
	$result = $conn->query($saleIDQuery);
	//
	if (!empty($result) && $result->num_rows > 0) {
		//incrementing ID by 1 (enabling auto-incrementing in phpmyadmin isn't possible due to existing foreign key relationships)
		while($row = $result->fetch_assoc()) {
			$saleID = $row['max_id']+1;
		}
	}
		
	$saleProductID = $_POST['sale_product_id'];
	$saleProductQuery = "SELECT * FROM inventory WHERE product_id='$saleProductID'";
	$result = $conn->query($saleProductQuery);
	if (!empty($result) && $result->num_rows > 0) {
		$isSetSale1 = true;
		while($row = $result->fetch_assoc()) {		
			$productCode = $row['product_id'];
		}
	} else {
		die('Please enter a valid product ID');
	}
	if(isset($_POST['check_box'])){
		$transactionID--;
		$saleID--;
		
	}
	if (isset($_POST['sale_price'])) {
		$isSetSale2 = true;
		$price = round($_POST['sale_price'], 2);
	}
	if(isset($_POST['quantity_sold'])) {
		$isSetSale3 = true;
		$quantity = $_POST['quantity_sold'];
		
	}
	if(isset($_POST['sale_emp_id'])){
		$empID = $_POST['sale_emp_id'];
		$employeeQuery = "SELECT * FROM employee WHERE employee_id=".$empID;
		$result = $conn->query($employeeQuery);
		if(!$result) {
			die('Sorry, but that login was invalid, please navigate to the last page and try again');
		} else {
			$isSetSale4 = true;
		}
	}
	//if all fields in sale have data in them, attempt to use that data to create a new transaction+sale
	if($isSetSale1 && $isSetSale2 && $isSetSale3 && $isSetSale4) {
		
		//logging transaction
		$newTransactionQuery = "INSERT INTO `transaction`(`transaction_id`, `transaction_datetime`, `employee_id`) VALUES('$transactionID', '$currDate', '$empID')";
		$result = $conn->query($newTransactionQuery);
		if(!$result) {
			die("Sorry, but we weren't able to log that transaction, please review your input(s)".$transactionID." ".$currDate." ".$emp_id);			
		} else {
			echo 'success!';
		}
		//logging sale
		$newSaleQuery = "INSERT INTO `sale` (`sale_id`, `product_id`, `transaction_id`, `quantity_sold`, `retail_price`) VALUES ('$saleID', '$productCode', '$transactionID', '$quantity', '$price')";
		$result = $conn->query($newSaleQuery);
		if(!$result) {
			die("Sorry, but a new sale could not be created. Please review your input(s).".$saleID."_".$transactionID."_".$productCode."_".$quantity."_".$price);
		} else {
			echo 'sale logged!';
		}
	}	
}

?>

<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/w3-theme-black.css">

<style>
.footerdown {
    position:absolute;
    bottom:0;
    margin-bottom: 3%;
  }

.formposition {
  position:relative;

  }

</style>

<body>

  <!--Navigation bar STARTS
    -Button for current screen gets higlighted pale red
    -Clicking logo at left returns user to initial navigation Homepage screen-->
    <div class="w3-bar w3-border-bottom" style="width:100%">

      <a href="homepage.html" class="w3-left" style="width:12.25%;">
      <img src="images/NEWnavbarlogo.png" style="width:100%;"></a>
  
      <a href="index.html" class="w3-bar-item w3-button w3-border-left w3-right w3-black w3-padding-24">LOG<br>OUT</a>
  
      <a href="employeeresources.html" class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24">EMPLOYEE<br>RESOURCES</a>
  
      <a href="transactionhistory.html" class="w3-bar-item w3-border-left w3-button w3-right w3-padding-24">TRANSACTION<br>HISTORY</a>
  
      <a href="inventory.html" class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 ">VIEW<br>INVENTORY</a>
  
      <a href="transaction.html" class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 w3-pale-red">NEW<br>TRANSACTION</a><!--Current screen-->
  
    </div>
    <!--Current screen-->

  </div>
  <!--Navigation bar ENDS-->

  <!--Container for transaction information forms STARTS-->
  <div class="w3-display-middle" style="width:90%;height:80%;margin-top:2.5%;margin-bottom:0.25%">

    <!--Enter Purchasing Information form STARTS-->
    <div class="w3-half w3-card-4 w3-light-grey w3-border" style="width:48%;height:95%;margin-right:2%">

        <div class="w3-container w3-center">
          <h2>Enter Purchasing Information</h2>
        </div>

       
        <form class="w3-container formposition" style="width:100%;height:90%" method="POST" action="transaction.php"><!--Action denotes URL or file to send form information to-->

          <p style="height:10%;">
            <label class="w3-text"><b>Enter item ID:</b></label>
            <input class="w3-input w3-border" id="purchase_product_id" name="purchase_product_id" type="text"
              placeholder="Enter item ID if existing item in inventory, or 0 for new items from suppliers">
          </p>

          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter supplier name:</b></label>
            <input class="w3-input w3-border" name="supplier_name" id="supplier_name" type="text" placeholder="ENTER SUPPLIER NAME HERE">
          </p>

          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter supplier price ($usd):</b></label>
            <input class="w3-input w3-border" name="supplier_price" id="supplier_price" type="text" step="0.01" min="0.01" placeholder="Ex: 6.50">
            <!--Step allows for cents instead of requiring full dollar values-->
          </p>

          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter quantity purchased:</b> </label>
            <input class="w3-input w3-border" name="quantity_purchased" type="text" placeholder="Ex: 123">
          </p>
			<p style="height:10%;">
            <label class="w3-text"><b>Confirm Employee ID:</b></label><br>
            <input type="text" name="purchase_emp_id" id="purchase_emp_id" class="w3-select w3-border" style="width:100%">
             
          </p>
          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter item description/comments:</b></label>
            <textarea class="w3-input w3-border" style="width:100%;max-width:100%;" name="purchase_item_description" id="purchase_item_description"
              type="text"
              placeholder="(Provide more in-depth description of item's appearance or features, if necessary"
              wrap="soft"></textarea>
            <!--Max height and width style requirements prevent user from resizing text box to be to large
                          -Soft wrap allows user to see all text entered in box without adding spaces to database entry-->
          </p>
          

          <!--Clear and Log Purchase buttons STARTS-->
          <footer class="footerdown" style="width:95%;">
            <!--Clears information typed and resets form-->
            <button class="w3-left w3-btn w3-dark-grey w3-xlarge" type="reset">Clear</button>
            
            <!--Sends information to file listed in 'action' at form start-->
            <input type="submit" class="w3-right w3-btn w3-dark-grey w3-xlarge" id="log_purchase" name="log_purchase">
          </footer>
          <!--Clear and Log Purchase buttons ENDS-->

        </form>

    </div>
    <!--Enter Purchasing Information form ENDS-->

    <!--Enter Sales Information form STARTS-->
    <div class="w3-half w3-card-4 w3-white w3-border" style="width:48%;height:95%;margin-left:2%;">

        <div class="w3-container w3-center">
          <h2>Enter Sales Information</h2>
        </div>

        <form class="w3-container formposition" style="width:100%;height:90%" method="POST" action="transaction.php">
          <!--Action denotes URL or file to send form information to-->

          <p style="height:10%;">
            <label class="w3-text"><b>Enter Item ID:</b></label><br>
            <input type="text" name="sale_product_id" id="sale_product_id" class="w3-select w3-border" name="option" style="width:100%">
             
          </p>
          
          <p style="height:10%">
            <label class="w3-text"><b>Tie to previous transaction? (check for 'yes'):</b></label><br>
            <!--Check box to assign same transaction ID and sale ID as previous transaction entered
             -Avoids creation of multiple 'transactions' being recorded for multiple products purchased by one customer-->
            <input class="w3-check" type="checkbox" name="check_box" id="check_box">
            
          </p>

          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter retail price ($usd):</b></label>
            <input class="w3-input w3-border" name="sale_price" id="sale_price" type="number" step="0.01" min="0.01" placeholder="Ex: 14.99">
            <!--Step allows for cents instead of requiring full dollar values-->
          </p>

          <p style="height:10%;">
            <label class="w3-text-black"><b>Enter quantity sold:</b> </label>
            <input class="w3-input w3-border" name="quantity_sold" id="quantity_sold" type="number" placeholder="Ex: 123">
          </p>
		  <p style="height:10%;">
            <label class="w3-text"><b>Confirm Employee ID:</b></label><br>
            <input type="text" name="sale_emp_id" id="sale_emp_id" class="w3-select w3-border" style="width:100%">
             
          </p>

          <!--Clear and Log Sale buttons STARTS-->
          <footer class="footerdown" style="width:95%;">
          
            <!--Clears information typed and resets form-->
            <button class="w3-left w3-btn w3-dark-grey w3-xlarge" type="reset">Clear</button>

            <!--Sends information to file listed in 'action' at form start-->
            <input type="submit" class="w3-left w3-btn w3-dark-grey w3-xlarge"id="logTransaction" name="logTransaction">
          
          </footer>
          <!--Clear and Log Sale buttons ENDS-->
          
        </form>
    </div>
    <!--Enter Sales Information form ENDS-->

  </div>
  <!--Container for transaction information forms ENDS-->

  <!--Page footer STARTS-->
  <img class="w3-display-bottommiddle" src="images/PageFooter.png" style="width:98%;margin-bottom:0.5%">
  <!--Page footer ENDS-->

</body>

</html>