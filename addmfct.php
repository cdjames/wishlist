<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - addmfct.php
*/ 
	include 'mysqli.php'; // get login credentials

	// select manufacturers with country and name
	// if empty, run the insert
	// otherwise just link it with the product
	$cty = $_POST['mfct_cty'];
	$mfct = $_POST['mfct'];
	if(!$product_id) {
		$product_id = $_POST['pid'];
	}

	$cty_name = ($cty) ? $cty : NULL;
	/* */
	// if($cty_name)
	// $action = "SELECT mfct_id FROM manufacturer WHERE name=? and country='" . $cty_name . "'";
	$action = "SELECT mfct_id FROM manufacturer WHERE name=? and country=?";
	// else
		// $action = "SELECT mfct_id FROM manufacturer WHERE name=? and country IS NULL";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	// if(!$stmt->bind_param("s", $mfct)){
	if(!$stmt->bind_param("ss", $mfct, $cty)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($mfct_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "mfct select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	// echo $mfct_id;
	if($mfct_id < 1){ // if there is no such manufacturer, create one
		$stmt->close();
		$action = "INSERT INTO manufacturer(country, name) VALUES (?, ?)";
		// $action = ($cty_name) ? "INSERT INTO manufacturer(country, name) VALUES (?, ?)"
						// : "INSERT INTO manufacturer(name) VALUES (?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if($cty){
			if(!$stmt->bind_param("ss", $cty_name, $mfct)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
		} else {
			if(!$stmt->bind_param("s", $mfct)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
		}
		
		if(!$stmt->execute()){
			echo "mfct insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$mfct_id = $stmt->insert_id;
		
	}
	$stmt->close();

	if(!($stmt = $mysqli->prepare("SELECT fk_mfct_id FROM mfct_product WHERE fk_product_id=?"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("i", $product_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($result)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "mfct_product select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	// make sure that the product doesn't already have a manufacturer
	// no result or result is not the same s the mfct_id
	// if($result < 1 || $result !== $mfct_id){
	if($result < 1){
		$stmt->close();
		$action = "INSERT INTO mfct_product(fk_product_id, fk_mfct_id) VALUES(?,?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $product_id, $mfct_id)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		if(!$stmt->execute()){
			echo "mkft_product insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		echo "success";
		$stmt->close();
	}
?>