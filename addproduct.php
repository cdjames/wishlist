<?php 
	// keys: {pid, "name":"","url":"","mfct":"", mfct_cty, "price","price_cents","store":"", store_url, "prod_url":"","bought":"no", listid, mid, sid}

	include 'mysqli.php'; // get login credentials

	$prod_name = $_POST['name'];
	$bought = $_POST['bought'];
	$product_id = $_POST['pid'];
	$listid = $_POST['listid'];
	echo $prod_name;
	if($prod_name){
		/* add a product */
		if(!($stmt = $mysqli->prepare("INSERT INTO product(name, photo_url) VALUES (?, ?)" ))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if(!$stmt->bind_param("ss", $prod_name, $_POST['url'])){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

		if(!$stmt->execute()){
			echo "product insert execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$product_id = $stmt->insert_id;
		$stmt->close();

		/* add a link to the list */
		echo $product_id;
		$listid = $_POST['listid'];
		if($product_id){
			if(!($stmt = $mysqli->prepare("INSERT INTO list_product(fk_product_id, fk_list_id) VALUES(?,?)" ))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->bind_param("ii", $product_id, $listid)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}

			if(!$stmt->execute()){
				echo "list_product execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			// $ $stmt->insert_id;
			$stmt->close();
		
	
			/* add a manufacturer to product */
			if($_POST['mfct']){
				include 'addmfct.php';
			}

			/* add a store to product */
			if($_POST['store']){
				include 'addstore.php';
			}

			if($_POST['bought']){
				// include "setbought.php?pid=$pid&listid=$listid&bought=$bought";
				include 'setbought.php';
			}
		}
	}
?>