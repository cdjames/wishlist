<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - addproduct.php
*/ 
	// keys: {pid, "name":"","url":"","mfct":"", mfct_cty, "price","price_cents","store":"", store_url, "prod_url":"","bought":"no", listid, mid, sid}

	include 'mysqli.php'; // get login credentials

	$prod_name = $_POST['name'];
	$bought = $_POST['bought'];
	$product_id = $_POST['pid'];
	$listid = $_POST['listid'];
	$url = $_POST['url'];
	

	/* check if product already exists */
	$action = "SELECT product_id FROM product WHERE name=?";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("s", $prod_name)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($pid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "store select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	
	if($pid < 1 && $prod_name){ // if there is no such product, create one
		$stmt->close();
		/* add a product */
		if(!($stmt = $mysqli->prepare("INSERT INTO product(name, photo_url) VALUES (?, ?)" ))){
			echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->bind_param("ss", $prod_name, $_POST['url'])){
			echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "product insert execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$product_id = $stmt->insert_id;
		
	} else {
		$product_id = $pid;
	}

	$stmt->close();

	$listid = $_POST['listid'];

	/* see if there is already a link to the product on the current list */
	$action = "SELECT fk_product_id FROM list_product WHERE fk_product_id=? and fk_list_id=?";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("ss", $product_id, $listid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($lid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "list_product select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();

	/* if no link, add a link to the list */
	if(!$lid && $product_id){
		$stmt->close();

		if(!($stmt = $mysqli->prepare("INSERT INTO list_product(fk_product_id, fk_list_id) VALUES(?,?)" ))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->bind_param("ii", $product_id, $listid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "list_product execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		$stmt->close();
	
		/* add a manufacturer to product */
		if($_POST['mfct']){
			include 'addmfct.php';
		}

		/* add a store to product */
		if($_POST['store']){
			include 'addstore.php';
		}

		/* update its purchased status */
		if($_POST['bought']){
			include 'setbought.php';
		}
	}
?>