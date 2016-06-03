<?php 

	if(!$mysqli){
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
		if($mysqli->connect_errno){
			echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	}

	// select manufacturers with country and name
	// if empty, run the insert
	// otherwise just link it with the product
	$cty = $_POST['mfct_cty'];
	$mfct = $_POST['mfct'];
	echo $mfct;
	$cty_name = ($cty) ? $cty : NULL;
	// if($cty_name) {echo $cty_name;}
	if($cty_name)
		$action = "SELECT mfct_id FROM manufacturer WHERE name=? and country='" . $cty_name . "'";
	else
		$action = "SELECT mfct_id FROM manufacturer WHERE name=? and country IS NULL";
		// $action = "SELECT mfct_id FROM manufacturer WHERE name=?";
	// $action .= ($cty_name) ? "=?" : " IS ?";
	if(!($stmt = $mysqli->prepare($action))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("s", $mfct)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($mfct_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	// echo $mfct_id;
	if($mfct_id < 1){
		$stmt->close();
		$action = ($cty_name) ? "INSERT INTO manufacturer(country, name) VALUES (?, ?)"
						: "INSERT INTO manufacturer(name) VALUES (?)";

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
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		$mfct_id = $stmt->insert_id;
		
	}
	$stmt->close();

	if(!($stmt = $mysqli->prepare("SELECT fk_mfct_id FROM mfct_product WHERE fk_product_id=?"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("i", $_POST['pid'])){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($result)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	$stmt->fetch();
	// make sure that the product doesn't already have a manufacturer
	if($result < 1 && $result !== $mfct_id){
		$stmt->close();
		$action = "INSERT INTO mfct_product(fk_product_id, fk_mfct_id) VALUES(?,?)";

		if(!($stmt = $mysqli->prepare($action))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if(!$stmt->bind_param("ii", $_POST['pid'], $mfct_id)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		echo "success";
	}
?>