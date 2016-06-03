<?php 
	// keys: {pid, "name":"","url":"","mfct":"", mfct_cty, "price","price_cents","store":"","prod_url":"","bought":"no", listid, mid, sid}

	if(!$mysqli){
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
		if($mysqli->connect_errno){
			echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	}
	if($_POST['name']){
		/* add a product */
		if(!($stmt = $mysqli->prepare("INSERT INTO product(name, photo_url) VALUES (?, ?)" ))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->bind_param("ss", $_POST['name'], $_POST['url'])){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$product_id = $stmt->insert_id;
		$stmt->close();

		/* add a link to the list */
		$listid = $_POST['listid'];

		if(!($stmt = $mysqli->prepare("INSERT INTO list_product(fk_product_id, fk_list_id) VALUES(?,?)" ))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->bind_param("ii", $product_id, $listid)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		// $ $stmt->insert_id;
		$stmt->close();
	}
	/* add a manufacturer to product */
	if($_POST['mfct']){
		include 'addmfct.php';
	}

	/* add a store to product */
	// if($_POST['store']){
	// 	include 'addstore.php';
	// }
?>