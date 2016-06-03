<?php
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

// $listid = $_GET['listid'] || $saved_list_id;
$listid = ($_GET) ? $_GET['listid'] : $saved_list_id;
// $userid = $_GET['userid'] || $saved_user_id;
// $fname = $_GET['fname'] || $saved_fname;

if(!($stmt = $mysqli->prepare("SELECT fname, lname FROM users WHERE user_id = (SELECT fk_user_id FROM list WHERE list_id = ?)" ))){
// if(!($stmt = $mysqli->prepare("SELECT fname, lname FROM users WHERE user_id = 3" ))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!($stmt->bind_param("i", $listid))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($newfname, $newlname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

while($stmt->fetch()){
$html = "<h1>".		 
			$newfname . " " . $newlname . "'s List
		</h1>
		<table id='lists'>
			<tr>
				<th>Date Created</th>
				<th>Date Updated</th>
			</tr>";
}
// dynamically add first user's list
// $stmt->close();
	if(!($stmt = $mysqli->prepare("SELECT DATE_FORMAT(l.created, '%M %D, %Y'), DATE_FORMAT(l.updated, '%M %D, %Y') FROM list l WHERE l.list_id = ?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $listid))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($created, $updated)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	while($stmt->fetch()){
	 $html = $html . "<tr>\n<td>\n" . $created . "\n</td>\n<td>\n" . $updated . "\n</td>";
	}
	// $stmt->close();
	
	$html = $html .	"</table><div>";
 
	if(!($stmt = $mysqli->prepare("SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url FROM users u INNER JOIN list l ON l.fk_user_id = u.user_id INNER JOIN list_product lp ON lp.fk_list_id = l.list_id INNER JOIN product p ON p.product_id = lp.fk_product_id INNER JOIN product_store ps ON ps.fk_product_id = p.product_id INNER JOIN stores s ON s.store_id = ps.fk_store_id INNER JOIN mfct_product mp ON p.product_id = mp.fk_product_id INNER JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id WHERE u.user_id = (SELECT fk_user_id FROM list WHERE list_id = ?)" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $listid))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($pname, $photourl, $bought, $price, $mname, $country, $sname, $produrl)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	while($stmt->fetch()){
	 $productinfo = "<div class=\"product\"><h1>$pname</h1>
				<h2>Made by <span>$mname</span></h2>
				<img src=\"$photourl\" width=\"100\" height=\"100\">
				<ul>";
		if ($bought) {
			$productinfo = $productinfo . "You own this!";
		} else {
			$productinfo = $productinfo . "<li>Buy at <a href=\"$produrl\">$sname</a> for <span>$price</span></li>";
		}
		$productinfo = $productinfo . "</ul></div>";
		$html = $html . $productinfo;
	}

	$stmt->close();

		$html = $html . "</div>";
		// if ($_GET) {
		// 	$html = $html . json_encode($_GET);
		// }
		echo $html;
?>