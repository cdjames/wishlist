<?php 
	// keys: {pid, "name":"","url":"","mfct":"", mfct_cty, "price","price_cents","store":"", store_url, "prod_url":"","upd_bought, listid, mid, sid}
	echo json_encode($_POST);
	// include 'mysqli.php'; // get login credentials

	// $pname = $_POST['name'];
	// $bought = $_POST['upd_bought'];
	// $pid = $_POST['pid'];
	// $listid = $_POST['listid'];
	// $mid = $_POST['mid'];
	// $sid = $_POST['sid'];
	// $photo_url = $_POST['url'];
	// $prod_url = $_POST['prod_url'];
	// $price_cents = $_POST['price_cents']
	// // $price = $_POST['price'] + ($price_cents / 100);
	// $price = $_POST['price'];
	// // $price += ($_POST['price_cents'] / 100);

	// // update everything!
	// $action = "UPDATE product p 
	// LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id
	// LEFT JOIN list l ON l.list_id = lp.fk_list_id
	// LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
	// LEFT JOIN stores s ON s.store_id = ps.fk_store_id
	// LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
	// LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
	// SET p.name=?, p.photo_url=?, lp.bought=?, lp.updated=CURRENT_TIMESTAMP, ps.fk_store_id=?, ps.price=?, ps.product_url=?, mp.fk_mfct_id=?
	// WHERE lp.fk_list_id = ? and p.product_id = ? and m.mfct_id = ? and s.store_id = ?";
		
	// if(!($stmt = $mysqli->prepare($action))){
	// 	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	// }
	// if(!$stmt->bind_param("ssiidsiiiii", $pname, $photo_url, $bought, $sid, $price, $prod_url, $mid, $listid, $pid, $mid, $sid)){
	// 	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// }
	// // if(!$stmt->bind_result($pid)){
	// // 	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// // }
	// if(!$stmt->execute()){
	// 	echo "product update execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// }
	// // $stmt->fetch();
	// echo var_dump($stmt);
	// // echo $store_url;
	// // echo $store_name;
	// if($pid < 1 && $prod_name){ // if there is no such store, create one
	// 	$stmt->close();
	// 	/* add a product */
	// 	if(!($stmt = $mysqli->prepare("INSERT INTO product(name, photo_url) VALUES (?, ?)" ))){
	// 		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	// 	}
	// 	if(!$stmt->bind_param("ss", $prod_name, $_POST['url'])){
	// 		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// 	}

	// 	if(!$stmt->execute()){
	// 		echo "product insert execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// 	}
	// 	$product_id = $stmt->insert_id;
		
	// } else {
	// 	$product_id = $pid;
	// }

	// $stmt->close();

	// // echo $product_id;
	// $listid = $_POST['listid'];

	// $action = "SELECT fk_product_id FROM list_product WHERE fk_product_id=? and fk_list_id=?";
		
	// if(!($stmt = $mysqli->prepare($action))){
	// 	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	// }
	// if(!$stmt->bind_param("ss", $product_id, $listid)){
	// 	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// }
	// if(!$stmt->bind_result($lid)){
	// 	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// }
	// if(!$stmt->execute()){
	// 	echo "list_product select execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// }
	// $stmt->fetch();

	// /* add a link to the list */
	// if(!$lid){
	// 	$stmt->close();

	// 	if(!($stmt = $mysqli->prepare("INSERT INTO list_product(fk_product_id, fk_list_id) VALUES(?,?)" ))){
	// 		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	// 	}
	// 	if(!$stmt->bind_param("ii", $product_id, $listid)){
	// 		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// 	}

	// 	if(!$stmt->execute()){
	// 		echo "list_product execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	// 	}
	// 	// $stmt->insert_id;
	// 	$stmt->close();
	
	// 	/* add a manufacturer to product */
	// 	if($_POST['mfct']){
	// 		include 'addmfct.php';
	// 	}

	// 	/* add a store to product */
	// 	if($_POST['store']){
	// 		include 'addstore.php';
	// 	}

	// 	if($_POST['bought']){
	// 		include 'setbought.php';
	// 	}
	// }
?>