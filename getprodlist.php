<?php
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - getprodlist.php
*/ 

	include 'mysqli.php'; // get login credentials

	$listid = ($_GET) ? $_GET['listid'] : $saved_list_id; // might be coming from main page or from ajax
	$html;

	if($listid && $listid !== "fail"){ // make sure we have a valid listid
		/* select all user info given a list id */
		if(!($stmt = $mysqli->prepare("SELECT fname, lname FROM users WHERE user_id = (SELECT fk_user_id FROM list WHERE list_id = ?)" ))){
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

		/* create custom elements */
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
	

		/* dynamically print first user's list */
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
		
		$html = $html .	"</table><div>";
	 
		/* get all product info given a list id */
		if(!($stmt = $mysqli->prepare("SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url, p.product_id, m.mfct_id, s.store_id FROM users u LEFT JOIN list l ON l.fk_user_id = u.user_id LEFT JOIN list_product lp ON lp.fk_list_id = l.list_id LEFT JOIN product p ON p.product_id = lp.fk_product_id LEFT JOIN product_store ps ON ps.fk_product_id = p.product_id LEFT JOIN stores s ON s.store_id = ps.fk_store_id LEFT JOIN mfct_product mp ON p.product_id = mp.fk_product_id LEFT JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id WHERE l.list_id = ? GROUP BY p.name" ))){
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

		// if items in list, for each item fetch and process (shouldn't be pname if no item)
		/* make a div for each item */
		while($stmt->fetch() && $pname){
			$productinfo = "<div class=\"product\" data-pid='$product_id' data-mid='$mfct_id' data-sid='$store_id'><h1>$pname</h1>";
		 	$productinfo .= ($mname) ? "<h2>Made by <span>$mname</span></h2>" : "";
			// check that url is actually a url
			$productinfo .=  (strpos($photourl, "http") !== false) ? "<img src=\"$photourl\" height=\"100\">" : "";
			$productinfo .= "<ul>";
			if($bought){
				$productinfo .= "<li>You own this! (It was \$$price)</li>"; 
			} else {
				if($sname){
					$productinfo .= "<li>Buy at ";
					$productinfo .= ($produrl) ? "<a href=\"$produrl\">$sname</a>" : $sname;
					$productinfo .= ($price) ? " for <span>$price</span></li>" : "</li>";
				}
				
			}
			$productinfo .= "</ul>
			<button class='prod_update'>Edit Info</button>";
			if($bought)
				$productinfo .= "<button class='got_it' data-id=0>I need more!</button>";
			else
				$productinfo .= "<button class='got_it' data-id=1>I got it!</button>";
			$productinfo .= "<button class='prod_remove'>Remove</button>
			</div>";
			$html .= $productinfo;
		}
		// if no items in list
		if(!$pname) {
			$productinfo .= "<h1>No items</h1>";
			$html .= $productinfo;
		}

		$stmt->close();

		$html = $html . "</div>";
	} else { // no valid listid; let the user know
		$html = "<h1>No user/list</h1>";
	}
	echo $html;
?>