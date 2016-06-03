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
					<!-- dynamically add from database -->
					<?php include 'getuserlist.php'; ?>
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
			<div id="allproducts">
			<!-- make product list -->
			<?php include 'getprodlist.php'; ?>
				
			</div>
		</div>
		<div class="outer">
			<div>
				<form id="addproduct" method="post"> <!-- UPDATE THIS -->
			      <fieldset>
			      	<input type="hidden" name="pid" id="add_prod_form_pid">
			        <legend>Add/Update Product</legend>
			        <label for="name">Name</label>
			        <input type="text" name="name" id="pr_name">
			        <label for="url">Photo URL</label>
			        <input type="text" name="url" id="url">
			        <input type="hidden" name="mid" id="add_prod_form_mid">
			        <label for="mfct">Manufacturer</label>
			        <input type="text" name="mfct" id="mfct">
			        <label for="mfct_cty">Manufacturer Country</label>
			        <input type="text" name="mfct_cty" id="mfct_cty">
			        <label for="price">Price</label>
			        <div id="price_outer">
				        <input type="number" value="10" name="price" id="price" min="0"><span> . </span> 
				        <input type="number" value="99" name="price_cents" id="price_cents" min="0" max="99"></div>
				    <input type="hidden" name="sid" id="add_prod_form_sid">
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