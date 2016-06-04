<?php
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - removeproduct.php
*/ 

	include 'mysqli.php'; // get login credentials

	$pid = $_GET['pid'];
	$listid = $_GET['listid'];

	/* actually only removes link to product from list. leave product in database */
	if(!($stmt = $mysqli->prepare("DELETE FROM list_product WHERE fk_product_id = ? and fk_list_id=?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->bind_param("ii", $pid, $listid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

	if(!$stmt->execute()){
		echo "list_product delete execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	echo "product $pid removed from list $listid"; // a little info for ajax, not vital
	$stmt->close();

?>