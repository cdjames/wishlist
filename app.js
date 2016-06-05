/*
	** Author: Collin James, CS 340
	** Date: 6/2/16
	** Description: Final Project - JS
*/
var YEARS = 100,
	CUR_YEAR = new Date().getFullYear(),
	OLDEST_USER = 36,
	MONTHS = {January: 31, February: 29, March: 31, April: 30, May: 31, June: 30, July: 31, August: 31, September: 30, October: 31, November: 30, December: 31};

var functions = [configForm, handleAddUser, handleListClick, handleAddProduct, 
				bindProductEdit, bindProductRemove, bindSetBought, handleUpdateProduct,
				handleSearch];
doOnLoad(functions);

function bindSetBought () {
	var buttons = document.getElementsByClassName('got_it');
	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (event) {
			var parent = event.target.parentElement,
				pid = parent.getAttribute('data-pid'),
				listid = document.getElementById('lists').getAttribute('data-id'),
				bought = event.target.getAttribute('data-id');
			handleSetBought(pid, listid, bought);
		});
	}
}
function bindProductRemove () {
	var buttons = document.getElementsByClassName('prod_remove');
	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (event) {
			var parent = event.target.parentElement,
				pid = parent.getAttribute('data-pid'),
				listid = document.getElementById('lists').getAttribute('data-id');
			handleRemoveProduct(pid, listid);
		});
	}
}

function bindProductEdit(){
	var buttons = document.getElementsByClassName('prod_update');
	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (event) {
			event.preventDefault();
			var parent = event.target.parentElement,
				pid = parent.getAttribute('data-pid'),
				mid = parent.getAttribute('data-mid'),
				sid = parent.getAttribute('data-sid'),
				listid = document.getElementById('lists').getAttribute('data-id');
			document.getElementById('update_prod_form_pid').value = pid;
			document.getElementById('update_prod_form_mid').value = mid;
			document.getElementById('update_prod_form_sid').value = sid;
			setUpdateProduct(pid, mid, sid, listid);
		});
	}
}

function setUpdateProduct (pid, mid, sid, listid) {
	// put stuff into update fields!
	var req = new XMLHttpRequest(),
		url = "getproductinfo.php?pid=" + pid + "&listid=" + listid;
	req.open("GET", url, true);
	req.addEventListener('load', function () {
		var data = JSON.parse(req.responseText);
		console.log(data);
		var ids = ["upd_pr_name", "upd_url", "upd_price", 
					"upd_price_cents", 
					// "upd_mfct", "upd_mfct_cty", "upd_store",
					// "upd_store_url",
					 "upd_prod_url"];
		setValuesById(ids, data);
		// handle "bought" radio inputs
		var radios = document.getElementsByName('upd_bought'),
			rdioIdx = Math.abs((data.bought - 1)); // change 0 to 1 and 1 to 0
		console.log(data.bought);
		console.log(rdioIdx);
		radios[rdioIdx].checked = true;
		// handle select
		var mfct_select = document.getElementsByName('mfct_select')[1];
		// console.log(mfct_select);
		mfct_select.value = data.mid;
		var store_select = document.getElementsByName('store_select')[1];
		// console.log(store_select);
		store_select.value = data.sid;
	});
	req.send();
}

function setValuesById (ids, value_obj) {
	var keys = Object.keys(value_obj);
	
	ids.forEach(function (id, idx) {
		if(id !== "")
			document.getElementById(id).value = value_obj[keys[idx]];
	}); 
}

function getMfctList (elements) {
	var req = new XMLHttpRequest();
	req.open("GET", "getmfctlist.php", true);
	req.addEventListener('load', function () {
		console.log(req.responseText);
		// var data = JSON.parse(req.responseText);
		for (var i = 0; i < elements.length; i++) {
			elements[i].innerHTML = req.responseText;
		}
		// getProductList(listid || data.listid);
	});
	req.send();
}

function getStoreList (elements) {
	var req = new XMLHttpRequest();
	req.open("GET", "getstorelist.php", true);
	req.addEventListener('load', function () {
		console.log(req.responseText);
		// var data = JSON.parse(req.responseText);
		for (var i = 0; i < elements.length; i++) {
			elements[i].innerHTML = req.responseText;
		}
		// getProductList(listid || data.listid);
	});
	req.send();
}

function handleSearch () {
	var searchForm = document.getElementById('search');
	searchForm.addEventListener('submit', function (event) {
		event.preventDefault();
		var formData = new FormData(searchForm);
		formData.append("listid", document.getElementById('lists').getAttribute('data-id'));
		var request = new XMLHttpRequest();
		request.open("POST", "searchproducts.php", true);
		request.addEventListener('load', function () {
			// console.log(JSON.parse(request.responseText));
			console.log(request.responseText);
			// get the new list of products
			document.getElementById('allproducts').innerHTML = request.responseText;
			bindProductEdit();
			bindProductRemove();
			bindSetBought();		
		});
		request.send(formData);
	});
}

function handleUpdateProduct () {
	var updProdForm = document.getElementById('updateproduct');
	updProdForm.addEventListener('submit', function (event) {
		event.preventDefault();
		var formData = new FormData(updProdForm);
		formData.append("listid", document.getElementById('lists').getAttribute('data-id'));
		var request = new XMLHttpRequest();
		request.open("POST", "updateproduct.php", true);
		request.addEventListener('load', function () {
			// console.log(JSON.parse(request.responseText));
			console.log(request.responseText);
			// get the new list of products
			getProductList(document.getElementById('lists').getAttribute('data-id'));		
		});
		request.send(formData);
	});
}

function handleSetBought (pid, listid, status) {
	var req = new XMLHttpRequest(),
		url = "setbought.php?pid=" + pid + "&listid=" + listid + "&bought=" + status;
	req.open("GET", url, true);
	req.addEventListener('load', function () {
		console.log(req.responseText);
		getProductList(document.getElementById('lists').getAttribute('data-id'));
	});
	req.send();
}

function handleRemoveProduct (pid, listid) {
	var req = new XMLHttpRequest();
	req.open("GET", "removeproduct.php?pid=" + pid + "&listid=" + listid, true);
	req.addEventListener('load', function () {
		console.log(req.responseText);
		getProductList(document.getElementById('lists').getAttribute('data-id'));
	});
	req.send();
}

function handleAddProduct () {
	var addProdForm = document.getElementById('addproduct');
	addProdForm.addEventListener('submit', function (event) {
		event.preventDefault();
		var formData = new FormData(addProdForm);
		formData.append("listid", document.getElementById('lists').getAttribute('data-id'));
		var request = new XMLHttpRequest();
		request.open("POST", "addproduct.php", true);
		request.addEventListener('load', function () {
			console.log(request.responseText);
			// get the new list of products
			getProductList(document.getElementById('lists').getAttribute('data-id'));
			getMfctList(document.getElementsByClassName('mfct_list'));
			getStoreList(document.getElementsByClassName('store_list'));
		});
		request.send(formData);
	});
}

function handleListClick () {
	var buttons = document.getElementsByClassName('list_button');
	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (event) {
			event.preventDefault();
			console.log(event.target);
			getProductList(event.target.getAttribute('data-id'));
		});
	}
}

function handleAddUser () {
	var addForm = document.getElementById('adduser');
	addForm.addEventListener('submit', function (event) {
		event.preventDefault();
		var formData = new FormData(addForm);
		var request = new XMLHttpRequest();
		request.open("POST", "adduser.php", true);
		request.addEventListener('load', function () {
			console.log(request.responseText);
			getUserList(request.responseText);
		});
		request.send(formData);

	});

	function getUserList (listid) {
		var req = new XMLHttpRequest();
		req.open("GET", "getuserlist.php?add=true", true);
		req.addEventListener('load', function () {
			console.log(req.responseText);
			var data = JSON.parse(req.responseText);
			document.getElementById('users').querySelector('tbody').innerHTML = data.list;
			handleListClick(); // add listeners to list buttons
			getProductList(listid || data.listid || 'fail');
		});
		req.send();
	}

	
}

function getProductList (listid) {
	var req = new XMLHttpRequest();
	console.log("listid = " + listid);
	req.open("GET", "getprodlist.php?add=true&listid=" + listid, true);
	req.addEventListener('load', function () {
		console.log(req.responseText);
		// var data = JSON.parse(req.responseText);
		document.getElementById('allproducts').innerHTML = req.responseText;
		bindProductEdit();
		bindProductRemove();
		bindSetBought();
	});
	req.send();
}

function configForm () {
	/* add year options */
	var years = document.getElementById('DOByears'),
		yroption;
	for (var i = 0; i < YEARS; i++) {
		yroption = document.createElement('option');
		yroption.textContent = yroption.value = CUR_YEAR - OLDEST_USER - i;
		years.appendChild(yroption);
	}
	/* add month options */
	var months = document.getElementById('DOBmonths'),
		themonths = Object.keys(MONTHS),
		mooption;
	for (var i = 0; i < themonths.length; i++) {
		mooption = document.createElement('option');
		mooption.textContent = themonths[i];
		mooption.value = i+1;
		months.appendChild(mooption);
	}

	addDays(31); // for January
	/* change days when months changed */
	months.addEventListener('change', function (event) {
		var selDay = document.getElementById('DOBdays').value,
			ndays = event.target.value;
		addDays(ndays);
		/* update day selection to previous selection */
		document.getElementById('DOBdays').value = (selDay <= ndays) ? selDay : ndays;	
	})
}

function addDays (ndays) {	
	var days = document.getElementById('DOBdays'),
		numdays = ndays,
		daoption;
		// console.log(numdays);
	days.textContent=''; // clear the options
	for (var i = 1; i <= numdays; i++) {
		daoption = document.createElement('option');
		daoption.textContent = i;
		daoption.value = i;
		days.appendChild(daoption);
	}
}

function doOnLoad (functions) {
	for (var i = 0; i < functions.length; i++) {
		document.addEventListener('DOMContentLoaded', functions[i]);
	}
}
