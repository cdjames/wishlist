<?php 

	include 'mysqli.php'; // get login credentials

	// select manufacturers with country and name
	// if empty, run the insert
	// otherwise just link it with the product
	$store_name = $_POST['store'];
	$store_url = $_POST['store_url'];
	$price = $_POST['price'];
	$price += ($_POST['price_cents'] / 100);
	echo $price;
	$prod_url = $_POST['prod_url'];
	if(!$product_id) {
		$product_id = $_POST['pid'];
	}

	$action = "SELECT store_id FROM stores WHERE store_name=? and store_url=?";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("ss", $store, $url)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($store_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "store select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	echo $store_url;
	echo $store_name;
	if($store_id < 1){ // if there is no such store, create one
		$stmt->close();
		$action = "INSERT INTO stores(store_url, store_name) VALUES (?, ?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if($cty){
			if(!$stmt->bind_param("ss", $store_url, $store_name)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
		} else {
			if(!$stmt->bind_param("s", $store_id)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
		}
		
		if(!$stmt->execute()){
			echo "store insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$store_id = $stmt->insert_id;
		
	}
	$stmt->close();

	if(!($stmt = $mysqli->prepare("SELECT fk_store_id FROM product_store WHERE fk_product_id=?"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("i", $product_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($result)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "store_product select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	// make sure that the product doesn't already have a manufacturer
	// no result or result is not the same s the mfct_id
	if($result < 1 || $result !== $store_id){
		$stmt->close();
		$action = "INSERT INTO product_store(fk_product_id, fk_store_id, price, product_url) VALUES(?,?,?,?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("iids", $product_id, $store_id, $price, $prod_url)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		if(!$stmt->execute()){
			echo "store_product insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		} else {
			echo "product_store insert success";
		}
		$stmt->close();
	}
?>