<?php
/*
** Author: Collin James, CS 340
** Date: 6/4/16
** Description: Final Project - mysqli.php
*/ 
	/* create the db connection */
	if(!$mysqli){
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu","jamesc2-db","moS6V4cjzEttrXwo","jamesc2-db");
		if($mysqli->connect_errno){
			echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	}
?>