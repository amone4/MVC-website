<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Logout extends Users {

	public function __construct() {
		parent::__construct();

		if (LoginSessions::validateLogin()) {
			Response::success('You have been successfully logged out');
			LoginSessions::unsetLoginSession();
		}
		Response::redirect('users/login');
	}
}