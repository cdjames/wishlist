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
				bindProductEdit, bindProductRemove, bindSetBought];
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
				pid = parent.getAttribute('data-pid');
			handleRemoveProduct(pid);
		});
	}
}

function bindProductEdit(){
	var buttons = document.getElementsByClassName('prod_update');
	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (event) {
			var parent = event.target.parentElement,
				pid = parent.getAttribute('data-pid'),
				mid = parent.getAttribute('data-mid'),
				sid = parent.getAttribute('data-sid');
			document.getElementById('add_prod_form_pid').value = pid;
			document.getElementById('add_prod_form_mid').value = mid;
			document.getElementById('add_prod_form_sid').value = sid;
		});
	}
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

function handleRemoveProduct (pid) {
	var req = new XMLHttpRequest();
	req.open("GET", "removeproduct.php?pid=" + pid, true);
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
		formData.append("years", document.getElementById('DOByears').value);
		formData.append("months", document.getElementById('DOBmonths').value);
		formData.append("days", document.getElementById('DOBdays').value);
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
			getProductList(listid || data.listid);
		});
		req.send();
	}

	
}

function getProductList (listid) {
	var req = new XMLHttpRequest();
	req.open("GET", "getprodlist.php?add=true&listid=" + listid, true);
	req.addEventListener('load', function () {
		// console.log(req.responseText);
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
