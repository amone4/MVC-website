<?php
/**
 * method redirects user to a location within the website
 * @param $location string
 */
function redirect($location) {
	header('Location: '.URLROOT.'/'.$location);
	exit();
}

/**
 * checks if the login session is valid or not
 * @return bool
 */
function validateLogin() {
	if (isset($_SESSION['user']) && !empty($_SESSION['user']) && ctype_digit($_SESSION['user'])) {
		return true;
	} else {
		unset($_SESSION['user']);
		return false;
	}
}