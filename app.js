/*
	** Author: Collin James, CS 340
	** Date: 6/2/16
	** Description: Final Project - JS
*/
var YEARS = 100,
	CUR_YEAR = new Date().getFullYear(),
	OLDEST_USER = 36,
	MONTHS = {January: 31, February: 29, March: 31, April: 30, May: 31, June: 30, July: 31, August: 31, September: 30, October: 31, November: 30, December: 31};

var functions = [configForm, handleAddUser];
doOnLoad(functions);

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
			var req = new XMLHttpRequest();
			req.open("GET", "getuserlist.php?add=true", true);
			req.addEventListener('load', function () {
				console.log(req.responseText);
				var data = JSON.parse(req.responseText);
				document.getElementById('users').querySelector('tbody').innerHTML = data.list;
			});
			req.send();
		});
		request.send(formData);
		// console.log(addForm.elements);
	});
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
