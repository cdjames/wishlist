<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - getmfctlist.php
*/ 
	include 'mysqli.php'; // get login credentials

	/* select all manufacturers */
	if(!($stmt = $mysqli->prepare("SELECT m.name, m.country, m.mfct_id FROM manufacturer m GROUP BY m.name, m.country, m.mfct_id" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($mname, $cty, $mid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	/* create custom select element */
	$select = "<select name='mfct_select' class='mfct_select'>";
	$options = "<option value='0'>N/A</option>";
	while($stmt->fetch()){
		$options .= "<option value='$mid'>\n" . $mname . (($cty) ? ", " . $cty : "") . "\n</option>";
	}
	$select .= $options;
	$select .= "</select>";
	$stmt->close();
	
	/* print or send to ajax */
	echo $select;
?>