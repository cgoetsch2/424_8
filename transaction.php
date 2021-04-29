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
$isSupplierPurchase = false;
$isSale = false;
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
$description = "";
$maxID = NULL;
$empID = NULL;
$product_id = 0;
$currDate = date('Y-m-d H:i:s');
$purchaseID = NULL;

$saleID = NULL;
$productID = NULL;
$maxProductID = NULL;
$transactionID = NULL;
$maxPurchaseID = NULL;
$price = 0;
$cost = 0;
$quantity = 0;


if (isset($_POST['purchase_product_name'])) {
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
	//querying supplier_purchase table to find max purchase_id
	$purchaseIDQuery = "SELECT MAX(purchase_id) AS max_id FROM supplier_purchase";
	$result = $conn->query($purchaseIDQuery);
	if (!empty($result) && $result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$maxpurchaseID = $row['max_id']+1;
		}
	}

	$productName= trim(strtoupper($_POST['purchase_product_name']));
	$purchaseProductQuery = "SELECT * FROM inventory WHERE product_name='$productName'";
	$result = $conn->query($purchaseProductQuery);
	if (!empty($result) && $result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {			
			$productID = $row['product_id'];
			$productName = $row['product_name'];
		}
	} else {
		$newProductIDQuery = "SELECT MAX(product_id) AS max_id FROM inventory";
		$result = $conn->query($newProductIDQuery);
		if(!empty($result) && $result ->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$productID = $row['max_id']+1;
				$maxProductID = $productID;
			}
		}
	}
	 
		
	if (isset($_POST['supplier_name'])) {
		
		$isSetPurchase2 = true;
		$supplierName = mysqli_real_escape_string($conn, trim(strtoupper($_POST['supplier_name'])));
		$maxPurchaseIDQuery = "SELECT MAX(purchase_id) as max_id FROM supplier_purchase";
		$result = $conn->query($maxPurchaseIDQuery);
		if(!empty($result) && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$maxPurchaseID = $row['max_id']+1;
			}
		}
		$supplierNameQuery = "SELECT * FROM supplier_purchase WHERE supplier_name='$supplierName'";
		$result = $conn->query($supplierNameQuery);
		if(!empty($result) && $result->num_rows > 0) {
			$purchaseID = $row['purchase_id'];
		} else {
			$purchaseID = $maxPurchaseID;
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
	if(isset($_POST['description']) && $_POST['description'] != "") {
		$isSetPurchase5 = true;
		$description = mysqli_real_escape_string($conn, $_POST['description']); 
		
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
		if($isSetPurchase5 && $productID == $maxProductID) {	
			$newProductQuery = "INSERT INTO `inventory` (`product_id`, `product_name`, `description`, `qty_in_stock`) VALUES ('$productID', '$productName', '$description', '$quantity')";
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
			echo 'Transaction success';
		}
		//logging new purchase
		$newPurchaseQuery = "INSERT INTO `supplier_purchase` ( `transaction_id`, `product_id`, `supplier_name`, `quantity_purchased`, `cost`) VALUES ('$transactionID', '$productID',"."'$supplierName'".", '$quantity', '$cost')";
		$result = $conn->query($newPurchaseQuery);
		if(!$result) {
			die("Sorry, but we weren't able to log that purchase, please review your input(s)".$purchaseID."_".$transactionID."_".$productID."_".$supplierName."_".$quantity."_".$cost."_".$conn->error);
		} else {
			echo 'Purchase success!';
		}
	} else {
		die($isSetPurchase1."_".$isSetPurchase2."_".$isSetPurchase3."_".$isSetPurchase4);
	}
//end of SUPPLIER PURCHASE
//beginning of RETAIL SALE		
} else if(isset($_POST['product_name'])) {

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
	if (!empty($result) && $result->num_rows > 0) {
		//incrementing ID by 1 
		while($row = $result->fetch_assoc()) {
			$saleID = $row['max_id']+1;
		}
	}
	//cleaning user input and searching the db for a match to the name
	$saleProductName = trim(strtoupper($_POST['product_name']));
	$saleProductQuery = "SELECT * FROM inventory WHERE product_name='$saleProductName'";
	$result = $conn->query($saleProductQuery);
	if (!empty($result) && $result->num_rows > 0) {
		$isSetSale1 = true;
		while($row = $result->fetch_assoc()) {		
			$productID = $row['product_id'];
		}
	} else {
		die('Please enter a valid product name');
	}
	//if linked to previous transaction (two transactions that share the same sale ID), decrement saleID so that it's equal to MAX(sale_id)-1
	if(isset($_POST['check_box'])){
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
		$newSaleQuery = "INSERT INTO `sale` (`sale_id`, `product_id`, `transaction_id`, `quantity_sold`, `retail_price`) VALUES ('$saleID', '$productID', '$transactionID', '$quantity', '$price')";
		$result = $conn->query($newSaleQuery);
		if(!$result) {
			die("Sorry, but a new sale could not be created. Please review your input(s).".$saleID."_".$transactionID."_".$productID."_".$quantity."_".$price);
		} else {
			echo 'sale logged!';
		}
	} else {
		die($saleID."_".$transactionID."_".$productID."_".$quantity."_".$price);
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
    position: absolute;
    bottom: 0;
    margin-bottom: 3%;
  }

  .formposition {
    position: relative;

  }
</style>

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

    <a href="inventory.php" class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 ">VIEW<br>INVENTORY</a>

    <a href="transaction.php"
      class="w3-bar-item w3-button w3-border-left w3-right w3-padding-24 w3-pale-red">NEW<br>TRANSACTION</a>
    <!--Current screen-->

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


      <form class="w3-container formposition" style="width:100%;height:90%" method="POST" action="transaction.php">
        <!--Action denotes URL or file to send form information to-->

        <p style="height:10%;">
          <label class="w3-text-black"><b>Confirm employee ID:</b></label>
          <input class="w3-input w3-border" name="purchase_emp_id" type="text" placeholder="Ex: 123">
        </p>

        <p style="height:10%;">
          <label class="w3-text"><b>Enter item name:</b></label>
          <input class="w3-input w3-border" name="purchase_product_name" type="text" placeholder="Ex: Moncler Suyen Hooded Down Parka">
        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter supplier name:</b></label>
          <input class="w3-input w3-border" name="supplier_name" type="text" placeholder="Ex: Lena's Textiles">
        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter supplier price ($usd):</b></label>
          <input class="w3-input w3-border" name="supplier_price" type="text" placeholder="Ex: 6.50">
          <!--Step allows for cents instead of requiring full dollar values-->
        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter quantity purchased:</b> </label>
          <input class="w3-input w3-border" name="quantity_purchased" type="text" placeholder="Ex: 123">
        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter item description/comments:</b></label>
          <textarea class="w3-input w3-border" style="width:100%;max-width:100%;" name="description" type="text"
            placeholder="(Provide more in-depth description of item's appearance or features, if necessary" wrap="soft">
          </textarea>
          <!--Max height and width style requirements prevent user from resizing text box to be to large
                          -Soft wrap allows user to see all text entered in box without adding spaces to database entry-->
        </p>


        <!--Clear and Log Purchase buttons STARTS-->
        <footer class="footerdown" style="width:95%;">
          <!--Clears information typed and resets form-->
          <button class="w3-left w3-btn w3-dark-grey w3-xlarge" type="reset">Clear</button>

          <!--Sends information to file listed in 'action' at form start-->
          <input class="w3-right w3-btn w3-dark-grey w3-xlarge" type="submit" id="logTransaction"
            name="logTransaction" value="Log Purchase">
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
          <label class="w3-text-black"><b>Confirm employee ID:</b></label>
          <input class="w3-input w3-border" name="sale_emp_id" type="text" placeholder="Ex: 123">
        </p>

        <p style="height:10%;">
          <label class="w3-text"><b>Select item name:</b></label><br>
          <select class="w3-select w3-border" name="product_name" style="width:100%">
			<?php
			$itemNameQuery = "SELECT * FROM inventory";
			$result = $conn->query($itemNameQuery);
			if(!empty($result) && $result->num_rows > 0) {
				while ($row = $result->fetch_assoc()){
					echo '<option value="'.$row['product_name'].'">';
					echo $row['product_name'];
				
				}
			}
			?> 
			</option> 
		
                        
            <!--Provides dropdown box for all item names currently in inventory for user to select when logging a sale
               -Prevents a mistype from updating the incorrect product in database (if a text input box were used instead)-->
          </select>
        </p>

        <p style="height:10%">
          <label class="w3-text"><b>Tie to previous transaction? (check for 'yes'):</b></label><br>
          <!--Check box to assign same transaction ID and sale ID as previous transaction entered
             -Avoids creation of multiple 'transactions' being recorded for multiple products purchased by one customer-->
          <input type="checkbox" name="check_box" class="w3-check">

        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter retail price ($usd):</b></label>
          <input class="w3-input w3-border" name="sale_price" type="text" placeholder="Ex: 14.99">
          <!--Step allows for cents instead of requiring full dollar values-->
        </p>

        <p style="height:10%;">
          <label class="w3-text-black"><b>Enter quantity sold:</b> </label>
          <input class="w3-input w3-border" name="quantity_sold" type="text" placeholder="Ex: 123">
        </p>

        <!--Clear and Log Sale buttons STARTS-->
        <footer class="footerdown" style="width:95%;">

          <!--Clears information typed and resets form-->
          <button class="w3-left w3-btn w3-dark-grey w3-xlarge" type="reset">Clear</button>

          <!--Sends information to file listed in 'action' at form start-->
          <input class="w3-right w3-btn w3-dark-grey w3-xlarge" type="submit" id="logTransaction"
            name="logTransaction" value="Log Sale">

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