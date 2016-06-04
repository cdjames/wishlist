<?php
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - setbought.php
*/

	include 'mysqli.php'; // get login credentials
	
	
	$bought = (!$bought) ? $_GET['bought'] : $bought;
	$product_id = (!$product_id) ? $_GET['pid'] : $product_id;
	$listid = (!$listid) ? $_GET['listid'] : $listid;

	/* update the purchases status of a product */
	if(!($stmt = $mysqli->prepare("UPDATE list_product SET bought=? WHERE fk_product_id = ? and fk_list_id = ?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->bind_param("iii", $bought, $product_id, $listid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

	if(!$stmt->execute()){
		echo "set bought execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	echo "bought set to $bought"; // nice message for ajax
	$stmt->close();

	function echoVars($vars)
	{
		for ($i=0, $arrlength = count($vars); $i < $arrlength; $i++) { 
			echo $vars[$i] . "\n";
		}
	}
?>