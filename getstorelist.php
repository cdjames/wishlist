<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - getstorelist.php
*/ 
	include 'mysqli.php'; // get login credentials

	/* select all stores */
	if(!($stmt = $mysqli->prepare("SELECT s.store_name, s.store_url, s.store_id FROM stores s GROUP BY s.store_name" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($sname, $url, $sid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

	/* create custom select element */
	$select = "<select name='store_select' class='store_select'>";
	$options = "<option value='0'>N/A</option>";
	while($stmt->fetch()){
		$options .= "<option value='$sid'>\n" . $sname . (($url) ? ", " . $url : "") . "\n</option>";
	}
	$select .= $options;
	$select .= "</select>";
	$stmt->close();
	
	/* print or send to ajax*/
	echo $select;
?>