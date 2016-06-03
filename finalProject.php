<!--
	** Author: Collin James, CS 340
	** Date: 5/4/16
	** Description: Final Project - HTML
-->
<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="style.css">
		<script src="app.js"></script>
	</head>
	<body>
		<!-- <script src="script.js"></script> -->
		<div class="outer">
			<div>
				<h1>User</h1>
				<table id="users">
					<tbody>
					<!-- <tr>
						<th>First name</th>
						<th>Last name</th>
						<th>Date of Birth</th>
						<th>List</th>
					</tr> -->
					<!-- dynamically add from database -->
<?php
	include 'getuserlist.php';
// if(!($stmt = $mysqli->prepare("SELECT u.fname, u.lname, u.dob, l.list_id FROM users u INNER JOIN list l ON l.fk_user_id = u.user_id GROUP BY u.lname, u.fname" ))){
// 	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
// }

// if(!$stmt->execute()){
// 	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
// }
// if(!$stmt->bind_result($fname, $lname, $dob, $listid)){
// 	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
// }
// $cnt = 0;
// $saved_list_id;
// $saved_fname;
// while($stmt->fetch()){
//  if ($cnt == 0) {
//  	$saved_list_id = $listid;
//  	$saved_fname = $fname;
//  }
//  echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $dob . "\n</td>\n<td><a href=\"list.php?id=" . $listid . "\">Mylist</a></td>\n</tr>";
//  $cnt++;
// }
// $stmt->close();
?>
					</tbody>
				</table>
			</div>

			<div>
				<form id="adduser" name="adduser" method="post"> <!-- UPDATE THIS -->
			      <fieldset>
			        <legend>Add/Update User</legend>
			        <label for="fname">Name</label>
			        <input type="text" name="fname" id="fname">
			        <label for="lname">List Name</label>
			        <input type="text" name="lname" id="lname">
			         <label for="DOByears">Date of birth</label>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <select id="DOByears" form="adduser">
			        </select>
			        <select id="DOBmonths" form="adduser">
			        </select>
			        <select id="DOBdays" form="adduser">
			        </select>
			        <select form="adduser"><option>howdy</option></select>
			        <input type="submit" id="add" value="Add User">
			        <input type="submit" id="update" value="Update User">
			      </fieldset>
			    </form>
		    </div>
		</div>

		<div class="outer">
			<div>
				<h1>
					<?php 
					echo "$saved_fname";
					?>'s List
				</h1>
				<table id="lists">
					<tr>
						<th>Date Created</th>
						<th>Date Updated</th>
					</tr>
<!-- dynamically add first user's list -->
<?php 
	if(!($stmt = $mysqli->prepare("SELECT DATE_FORMAT(l.created, '%M %D, %Y'), DATE_FORMAT(l.updated, '%M %D, %Y') FROM list l WHERE l.list_id = ?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $saved_list_id))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($created, $updated)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	while($stmt->fetch()){
	 echo "<tr>\n<td>\n" . $created . "\n</td>\n<td>\n" . $updated . "\n</td>";
	}
	$stmt->close();
?>
				</table>
				<div>
<?php 
	if(!($stmt = $mysqli->prepare("SELECT p.name, p.photo_url, lp.bought, ps.price, m.name, m.country, s.store_name, ps.product_url FROM users u 
		INNER JOIN list l ON l.fk_user_id = u.user_id
		INNER JOIN list_product lp ON lp.fk_list_id = l.list_id
		INNER JOIN product p ON p.product_id = lp.fk_product_id
		INNER JOIN product_store ps ON ps.fk_product_id = p.product_id
		INNER JOIN stores s ON s.store_id = ps.fk_store_id
		INNER JOIN mfct_product mp ON p.product_id = mp.fk_product_id
		INNER JOIN manufacturer m ON m.mfct_id = mp.fk_mfct_id
		WHERE u.user_id = ?" ))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i", $saved_list_id))){
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
		echo $productinfo;
	}

	$stmt->close();
?>
					</div>
				</div>
			</div>
		</div>
		<div class="outer">
			<div>
				<form id="addproduct" method="post"> <!-- UPDATE THIS -->
			      <fieldset>
			        <legend>Add/Update Product</legend>
			        <label for="name">Name</label>
			        <input type="text" name="name" id="pr_name">
			        <label for="url">Photo URL</label>
			        <input type="text" name="url" id="url">
			        <label for="mfct">Manufacturer</label>
			        <input type="text" name="mfct" id="mfct">
			        <label for="price">Price</label>
			        <div id="price_outer">
				        <input type="number" value="10" name="price" id="price" min="0"><span> . </span> 
				        <input type="number" value="99" name="price_cents" id="price_cents" min="0" max="99"></div>
			        <label for="store">Store Name</label>
			        <input type="text" name="store" id="store">
			        <label for="prod_url">Product Url</label>
			        <input type="text" name="prod_url" id="prod_url">
			        <label for="bought">Bought</label>
			        <div>
				        <input type="radio" name="bought" id="bought_yes" value="yes">
				        <input type="radio" name="bought" id="bought_no" value="no" checked>
				    </div>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <input type="submit" id="pr_add" value="Add Product">
			        <input type="submit" id="pr_update" value="Update Product">
			      </fieldset>
			    </form>
		    </div>
		</div>
	</body>
</html>	   