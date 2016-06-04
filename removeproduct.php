<?php

include 'mysqli.php'; // get login credentials

$pid = $_GET['pid'];
$listid = $_GET['listid'];

if(!($stmt = $mysqli->prepare("DELETE FROM list_product WHERE fk_product_id = ? and fk_list_id=?" ))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->bind_param("ii", $pid, $listid)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(!$stmt->execute()){
	echo "list_product delete execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// $product_id = $stmt->insert_id;
echo "product $pid removed from list $listid";
$stmt->close();

?>