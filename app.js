/*
	** Author: Collin James, CS 340
	** Date: 5/4/16
	** Description: Final Project - JS
*/
var YEARS = 100,
	CUR_YEAR = new Date().getFullYear(),
	OLDEST_USER = 13,
	MONTHS = {January: 31, February: 29, March: 31, April: 30, May: 31, June: 30, July: 31, August: 31, September: 30, October: 31, November: 30, December: 31};

document.addEventListener('DOMContentLoaded', configForm);

function configForm () {
	/* add year options */
	var years = document.getElementById('DOByears'),
		yroption;
	for (var i = 0; i < YEARS; i++) {
		yroption = document.createElement('option');
		yroption.textContent = CUR_YEAR - OLDEST_USER - i;
		years.appendChild(yroption);
	}
	/* add month options */
	var months = document.getElementById('DOBmonths'),
		themonths = Object.keys(MONTHS),
		mooption;
	for (var i = 0; i < themonths.length; i++) {
		mooption = document.createElement('option');
		mooption.textContent = themonths[i];
		months.appendChild(mooption);
	}

	addDays(31); // for January
	/* change days when months changed */
	months.addEventListener('change', function (event) {
		var selDay = document.getElementById('DOBdays').value,
			ndays = MONTHS[event.target.value];
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
			days.appendChild(daoption);
		}
	}