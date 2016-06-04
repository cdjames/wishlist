<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - adduser.php
*/
	/* keys: fname, lname, years, months, days */
	
	//Connects to the database
	include 'mysqli.php'; // get login credentials

	$months = ($_POST['months'] < 10) ? "0" . $_POST['months'] : $_POST['months'];
	$days = ($_POST['days'] < 10) ? "0" . $_POST['days'] : $_POST['days'];
	$dob = $_POST['years'] . "-" . $months . "-" . $days;
	$userid;

	/* create a user */
	if(!($stmt = $mysqli->prepare("INSERT INTO users(fname, lname, dob) VALUES (?, ?, ?)"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!($stmt->bind_param("sss",$_POST['fname'],$_POST['lname'],$dob))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	} else {
		$userid = $stmt->insert_id;
	}

	/* create list for user */
	if(!($stmt = $mysqli->prepare("INSERT INTO list(updated, fk_user_id) VALUES (CURRENT_TIMESTAMP, ?)"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $userid))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}
	echo $stmt->insert_id;
	$stmt->close();

?>