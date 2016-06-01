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
else {
	echo "mysqli success";
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
					<tr>
						<th>First name</th>
						<th>Last name</th>
						<th>Date of Birth</th>
						<th>List</th>
					</tr>
					<tr>
						<td>John</td>
						<td>Smith</td>
						<td>1980-03-13</td>
						<!-- example php anchor; want to change later, make dynamic -->
						<td><a href="list.php?id=1">Mylist</a></td>
					</tr>
				</table>
			</div>

			<div>
				<form id="adduser" method="post"> <!-- UPDATE THIS -->
			      <fieldset>
			        <legend>Add/Update User</legend>
			        <label for="name">Name</label>
			        <input type="text" name="name" id="name">
			        <label for="listname">List Name</label>
			        <input type="text" name="listname" id="listname">
			         <label for="DOB">Date of birth</label>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <select id="DOByears">
			        </select>
			        <select id="DOBmonths">
			        </select>
			        <select id="DOBdays">
			        </select>
			        <input type="submit" id="add" value="Add User">
			        <input type="submit" id="update" value="Update User">
			      </fieldset>
			    </form>
		    </div>
		</div>

		<div class="outer">
			<div>
				<h1>User's List</h1>
				<table id="lists">
					<tr>
						<th>Date Created</th>
						<th>Date Updated</th>
					</tr>
					<tr>
  						<td>1980-03-13</td>
  						<td>1980-03-13</td>
						<!-- example php anchor; want to change later, make dynamic -->
					</tr>
				</table>
				<div>
					<div>
						<h1>iPod</h1>
						<h2>Made by <span>Apple</span></h2>
						<img src="">
						<ul>
							<li>Buy at <a href="#">Amazon</a> for <span>299.99</span></li>
						</ul>
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