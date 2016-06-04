<?php 
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - getuserlist.php
*/ 
	include 'mysqli.php'; // get login credentials

	/* get a list of all users */
	if(!($stmt = $mysqli->prepare("SELECT u.fname, u.lname, u.dob, l.list_id, u.user_id FROM users u INNER JOIN list l ON l.fk_user_id = u.user_id GROUP BY u.lname, u.fname" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($fname, $lname, $dob, $listid, $userid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	/* create rows for each user */
	$cnt = 0;
	$saved_list_id;
	$saved_user_id;
	$saved_fname;
	$list = "<tr>
				<th>First name</th>
				<th>Last name</th>
				<th>Date of Birth</th>
				<th>List</th>
			</tr>";
	while($stmt->fetch()){
	 if ($cnt == 0) { // save the first user info in order to display that list later
	 	$saved_list_id = $listid;
	 	$saved_user_id = $userid;
	 	$saved_fname = $fname;
	 }
	 $list = $list . "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $dob . "\n</td>\n<td><button class=\"list_button\" data-id=\"" . $listid . "\">list</button></td>\n</tr>";
	 $cnt++;
	}
	$stmt->close();
	/* create an object to send back to ajax */
	$data['listid'] = $saved_list_id;
	$data['list'] = $list;

	if ($_GET['add']) { // to ajax
		echo json_encode($data);
	} else { // print
		echo $list;
	}
?>