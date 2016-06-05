<!--
	** Author: Collin James, CS 340
	** Date: 6/4/16
	** Description: Final Project - HTML
-->
<?php
	//Turn on error reporting
	ini_set('display_errors', 'On');
	
	//Connects to the database
	include 'mysqli.php'; 
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="public/style.css">
		<script src="public/app.js"></script>
	</head>
	<body>
		<!-- <script src="script.js"></script> -->
		<div class="outer">
			<div>
				<h1>Users</h1>
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
			        <legend>Add User</legend>
			        <label for="fname">Name</label>
			        <input type="text" name="fname" id="fname" required>
			        <label for="lname">List Name</label>
			        <input type="text" name="lname" id="lname" required>
			         <label for="DOByears">Date of birth</label>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <select id="DOByears" name="years" form="adduser">
			        </select>
			        <select id="DOBmonths" name="months" form="adduser">
			        </select>
			        <select id="DOBdays" name="days" form="adduser">
			        </select>
			        <input type="submit" id="add" value="Add User">
			        <!-- <input type="submit" id="update" value="Update User"> -->
			      </fieldset>
			    </form>
		    </div>

		    <div id="search_outer">
		    	<form id="search">
			    	<label for="search_field">Find products under price:</label>
			    	<input id="search_field" name="price" type="number" value="15">
			    	<input type="submit" value="Search">
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
			        <legend>Add Product</legend>
			        <label for="name">Name</label>
			        <input type="text" name="name" id="pr_name" required>
			        <label for="url">Photo URL</label>
			        <input type="text" name="url" id="url">
			        <input type="hidden" name="mid" id="add_prod_form_mid">
			        <label for="mfct_select" style='display: none;'>Manufacturer</label>
			        <div class="mfct_list" style='display: none;'>
				        <?php include 'getmfctlist.php' ?>
				    </div>
			        <label for="mfct">New Manufacturer</label>
			        <input type="text" name="mfct" id="mfct">
			        <label for="mfct_cty">New Manufacturer Country</label>
			        <input type="text" name="mfct_cty" id="mfct_cty">
			        <label for="price">Price</label>
			        <div class="price_outer">
				        <input type="number" value="10" name="price" id="price" min="0"><span> . </span> 
				        <input type="number" value="99" name="price_cents" id="price_cents" min="0" max="99"></div>
				    <input type="hidden" name="sid" id="add_prod_form_sid">
				    <label for="store_select" style='display: none;'>Store</label>
				    <div class="store_list" style='display: none;'>
				        <?php include 'getstorelist.php' ?>
				    </div>
			        <label for="store">New Store Name</label>
			        <input type="text" name="store" id="store">
			        <label for="store_url">New Store Url</label>
			        <input type="text" name="store_url" id="store_url">
			        <label for="prod_url">Product Url</label>
			        <input type="text" name="prod_url" id="prod_url">
			        <label for="bought">Bought</label>
			        <div class="radios">
				        <label for="bought_yes">Yes</label><input type="radio" name="bought" id="bought_yes" value="1">
				        <label for="bought_no">No</label><input type="radio" name="bought" id="bought_no" value="0" checked>
				    </div>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <input type="submit" id="pr_add" value="Add Product">
			        <!-- <input type="submit" id="pr_update" value="Update Product"> -->
			      </fieldset>
			    </form>
		    </div>
		</div>
		<div class="outer">
			<div>
				<form id="updateproduct" method="post"> <!-- UPDATE THIS -->
			      <fieldset>
			      	<input type="hidden" name="pid" id="update_prod_form_pid">
			        <legend>Update Product</legend>
			        <label for="name">Name</label>
			        <input type="text" name="name" id="upd_pr_name" required>
			        <label for="url">Photo URL</label>
			        <input type="text" name="url" id="upd_url">
			        <input type="hidden" name="mid" id="update_prod_form_mid">
			        <label for="mfct_select">Manufacturer</label>
			        <div class="mfct_list">
				        <?php include 'getmfctlist.php' ?>
				    </div>
			        <!-- <input type="text" name="mfct" id="upd_mfct">
			        <label for="mfct_cty">Manufacturer Country</label>
			        <input type="text" name="mfct_cty" id="upd_mfct_cty"> -->
			        <label for="price">Price</label>
			        <div class="price_outer">
				        <input type="number" value="10" name="price" id="upd_price" min="0"><span> . </span> 
				        <input type="number" value="99" name="price_cents" id="upd_price_cents" min="0" max="99"></div>
				    <input type="hidden" name="sid" id="update_prod_form_sid">
			        <label for="store_select">Store</label>
			        <div class="store_list">
				        <?php include 'getstorelist.php' ?>
				    </div>
			        <!-- <input type="text" name="store" id="upd_store">
			        <label for="store_url">Store Url</label>
			        <input type="text" name="store_url" id="upd_store_url"> -->
			        <label for="prod_url">Product Url</label>
			        <input type="text" name="prod_url" id="upd_prod_url">
			        <label for="upd_bought">Bought</label>
			        <div class="radios">
				        <label for="upd_bought_yes">Yes</label><input type="radio" name="upd_bought" id="upd_bought_yes" value="1">
				        <label for="upd_bought_no">No</label><input type="radio" name="upd_bought" id="upd_bought_no" value="0" checked>
				    </div>
			        <!-- <input type="text" name="DOB" id="DOB"> -->
			        <!-- <input type="submit" id="pr_add" value="Add Product"> -->
			        <input type="submit" id="pr_update" value="Update Product">
			      </fieldset>
			    </form>
		    </div>
		</div>
	</body>
</html>	   