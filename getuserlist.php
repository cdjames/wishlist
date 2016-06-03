<?php 
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
	if($mysqli->connect_errno){
		echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!($stmt = $mysqli->prepare("SELECT u.fname, u.lname, u.dob, l.list_id, u.user_id FROM users u INNER JOIN list l ON l.fk_user_id = u.user_id GROUP BY u.lname, u.fname" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($fname, $lname, $dob, $listid, $userid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
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
	 if ($cnt == 0) {
	 	$saved_list_id = $listid;
	 	$saved_user_id = $userid;
	 	$saved_fname = $fname;
	 }
	 $list = $list . "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $dob . "\n</td>\n<td><button class=\"list_button\" data-id=\"" . $listid . "\">list</button></td>\n</tr>";
	 $cnt++;
	}
	$stmt->close();
	$data['listid'] = $saved_list_id;
	// $data['userid'] = $userid;
	$data['list'] = $list;
	if ($_GET['add']) {
		echo json_encode($data);
	} else {
		echo $list . $saved_list_id;
	}
?>