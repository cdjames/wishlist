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
		<table id='lists' data-id='$listid'>
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
 
	if(!($stmt = $mysqli->prepare("SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url, p.product_id, m.mfct_id, s.store_id FROM users u LEFT JOIN list l ON l.fk_user_id = u.user_id LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id LEFT JOIN product p ON p.product_id = lp.fk_product_id LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id LEFT JOIN stores s ON s.store_id = ps.fk_store_id LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id WHERE l.list_id = ?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $listid))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($pname, $photourl, $bought, $price, $mname, $country, $sname, $produrl, $product_id, $mfct_id, $store_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	while($stmt->fetch()){
		$productinfo = "<div class=\"product\" data-pid='$product_id' data-mid='$mfct_id' data-sid='$store_id'><h1>$pname</h1>";
	 	$productinfo .= ($mname) ? "<h2>Made by <span>$mname</span></h2>" : "";
		// check that url is actually a url
		$productinfo .=  (strpos($photourl, "http") !== false) ? "<img src=\"$photourl\" width=\"100\" height=\"100\">" : "";
		$productinfo .= "<ul>";
		if($bought){
			$productinfo .= "<li>You own this!</li>"; 
		} else {
			if($sname){
				$productinfo .= "<li>Buy at ";
				$productinfo .= ($produrl) ? "<a href=\"$produrl\">$sname</a>" : $sname;
				$productinfo .= ($price) ? " for <span>$price</span></li>" : "</li>";
			}
			
		}
		$productinfo .= "</ul><button class='prod_update'>Edit Product</button></div>";
		$html .= $productinfo;
	}

	$stmt->close();

		$html = $html . "</div>";
		// if ($_GET) {
		// 	$html = $html . json_encode($_GET);
		// }
		echo $html;
?>