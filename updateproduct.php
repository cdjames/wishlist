<?php
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - updateproduct.php
*/ 
	// keys: {pid, "name":"","url":"","mfct":"", mfct_cty, "price","price_cents","store":"", store_url, "prod_url":"","upd_bought, listid, mid, sid}
	
	$mysqli;
	include 'mysqli.php'; // get login credentials

	/* get post data */
	$pname = $_POST['name'];
	$bought = $_POST['upd_bought'];
	$pid = $_POST['pid'];
	$listid = $_POST['listid'];
	$mid = $_POST['mfct_select'];
	$sid = $_POST['store_select'];
	$photo_url = $_POST['url'];
	$prod_url = $_POST['prod_url'];
	$price = $_POST['price'];
	$price += ($_POST['price_cents'] / 100);
	var_dump($price);

	// update everything!
	$action = "UPDATE product SET name='hi' WHERE product_id=?";
	$action = "UPDATE product p LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id LEFT JOIN list l ON l.list_id = lp.fk_list_id LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id LEFT JOIN stores s ON s.store_id = ps.fk_store_id LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id SET p.name=?, p.photo_url=?, lp.bought=?, l.updated=CURRENT_TIMESTAMP, ps.price=?, ps.product_url=? WHERE lp.fk_list_id = ? and p.product_id = ? and m.mfct_id = ? and s.store_id = ?";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->bind_param("ssidsiiii", $pname, $photo_url, $bought, $price, $prod_url, $listid, $pid, $mid, $sid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	if(!$stmt->execute()){
		echo "product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	$stmt->close();

	/* update manufacturer of product*/
	if($mid){
		echo $mid . " " . $pid;
		$action = "UPDATE mfct_product SET fk_mfct_id=? WHERE fk_product_id=?";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $mid, $pid)){
		// if(!$stmt->bind_param("i", $pid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "mfct_product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		$stmt->close();
	}

	/* update store of product*/
	if($sid){
		$action = "UPDATE product_store SET fk_store_id=? WHERE fk_product_id=?";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $sid, $pid)){
		// if(!$stmt->bind_param("i", $pid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "mfct_product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$stmt->close();
	}

?>