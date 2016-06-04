<?php
	include 'mysqli.php';

	$pid = $_GET['pid'];

	// $action = "SELECT DISTINCT p.name, p.photo_url, lp.bought, FLOOR(ps.price) AS dollars, FLOOR(((ps.price - FLOOR(ps.price)) * 100)) AS cents, m.name, m.country, s.store_name, s.store_url, ps.product_url, m.mfct_id, s.store_id FROM product p 
	$action = "SELECT DISTINCT p.name, p.photo_url, lp.bought, FLOOR(ps.price) AS dollars, FLOOR(((ps.price - FLOOR(ps.price)) * 100)) AS cents, ps.product_url, m.mfct_id, s.store_id FROM product p 
	LEFT JOIN list_product lp ON lp.fk_product_id = p.product_id
	LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id
	LEFT JOIN stores s ON s.store_id = ps.fk_store_id
	LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id
	LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
	WHERE p.product_id = ? GROUP BY p.name";

	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $pid))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "product select Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	// if(!$stmt->bind_result($pname, $pho_url, $bought, $dollars, $cents, $mname, $country, $sname, $st_url, $pr_url, $mid, $sid)){
	if(!$stmt->bind_result($pname, $pho_url, $bought, $dollars, $cents, $pr_url, $mid, $sid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

	$stmt->fetch();

	$data = array("pname"=>$pname, "pho_url"=>$pho_url, "dollars"=>$dollars, "cents"=>$cents,
					// "mname"=>$mname, "country"=>$country, "sname"=>$sname, "st_url"=>$st_url, "pr_url"=>$pr_url,
					"pr_url"=>$pr_url, "mid"=>$mid, "sid"=>$sid, "bought"=>$bought);

	echo json_encode($data);
?>