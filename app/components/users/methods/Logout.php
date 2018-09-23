<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Logout extends Users {

	public function __construct() {
		parent::__construct();

		if (Misc::validateLogin()) OutputController::success('You have been successfully logged out');
		if (isset($_SESSION['user'])) unset($_SESSION['user']);
		OutputController::redirect('users/login');
	}
}