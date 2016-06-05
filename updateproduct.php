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
	$oldmid = $_POST['mid'];
	$mid = $_POST['mfct_select'];
	$sid = $_POST['store_select'];
	$photo_url = $_POST['url'];
	$prod_url = $_POST['prod_url'];
	$price = $_POST['price'];
	$price += ($_POST['price_cents'] / 100);
	var_dump($price);

	// update everything!
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
	/* check whether a mfct link exists */
	$action = "SELECT fk_mfct_id FROM mfct_product WHERE fk_product_id=?";
		
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->bind_param("i", $pid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	if(!$stmt->bind_result($existmid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

	if(!$stmt->execute()){
		echo "product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	$stmt->close();

	/* update manufacturer of product*/
	if($existmid && $mid){
		
		$action = "UPDATE mfct_product SET fk_mfct_id=? WHERE fk_product_id=?";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $mid, $pid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "mfct_product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		$stmt->close();
	} elseif (!$existmid && $mid) { // no mfct linked, so link one
		$action = "INSERT INTO mfct_product VALUES(?, ?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $pid, $mid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "mfct_product insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		$stmt->close();
	}

	/* update linked store of product*/
	if($sid){
		$action = "UPDATE product_store SET fk_store_id=? WHERE fk_product_id=?";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $sid, $pid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "mfct_product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		/* if no result, try adding a link to a store */
		if($stmt->affected_rows === 0)
		{
			/* check whether a link already exists */
			$action = "SELECT fk_store_id FROM product_store WHERE fk_product_id=?";
		
			if(!($stmt = $mysqli->prepare($action))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}

			if(!$stmt->bind_param("i", $pid)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			
			if(!$stmt->bind_result($existsid)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}

			if(!$stmt->execute()){
				echo "ps select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			$stmt->fetch();
			$stmt->close();
			/* if no link to a store, add one */
			if(!$existsid) {
				$action = "INSERT INTO product_store(fk_product_id, fk_store_id, price, product_url) VALUES(?,?,?,?)";

				if(!($stmt = $mysqli->prepare($action))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->bind_param("iids", $pid, $sid, $price, $prod_url)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				if(!$stmt->execute()){
					echo "product_store insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}

				$stmt->close();
			}
		}
	} 

?>