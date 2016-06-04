<?php

include 'mysqli.php'; // get login credentials

$pid = $_GET['pid'];

if(!($stmt = $mysqli->prepare("DELETE FROM product WHERE product_id = ?" ))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->bind_param("i", $pid)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(!$stmt->execute()){
	echo "product delete execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// $product_id = $stmt->insert_id;
echo "product $pid removed";
$stmt->close();

?>