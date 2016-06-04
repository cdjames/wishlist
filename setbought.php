<?php

include 'mysqli.php'; // get login credentials
// echo "pid = $pid";
// echo "listid = $listid";
echoVars(array($bought));
$bought = (!$bought) ? $_GET['bought'] : $bought;
$product_id = (!$product_id) ? $_GET['pid'] : $product_id;
$listid = (!$listid) ? $_GET['listid'] : $listid;
echoVars(array($product_id, $listid, $bought, json_encode($_GET)));

if(!($stmt = $mysqli->prepare("UPDATE list_product SET bought=? WHERE fk_product_id = ? and fk_list_id = ?" ))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->bind_param("iii", $bought, $product_id, $listid)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(!$stmt->execute()){
	echo "set bought execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// $product_id = $stmt->insert_id;
echo "bought set to $bought";
$stmt->close();

function echoVars($vars)
{
	for ($i=0, $arrlength = count($vars); $i < $arrlength; $i++) { 
		echo $vars[$i] . "\n";
	}
}
?>