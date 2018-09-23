<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Login extends Users {

	public function __construct() {
		parent::__construct();

		// checking if the user is logged in
		if (Misc::validateLogin()) {
			OutputController::info('You can\'t login, because you\'re logged in');
			App::dispatchMethod('logout');
		}

		// checking if the form is submitted
		if (Forms::isSubmitted()) {
			// checking if the form fields are filled
			if (Forms::post($p, ['username', 'password'])) {
				// sanitizing data
				$p = filter_var_array($p, FILTER_SANITIZE_STRING);
				// validating username
				if (Validations::username($p['username'])) {
					// validating password
					if (Validations::password($p['password'])) {
						// checking if username exists
						if (($row = $this->model->selectWhere(['username' => $p['username']])) && $this->model->rowCount() === 1) {
							// verifying if the password is correct
							if (password_verify($p['password'], $row->password)) {

								// encrypting and storing the session
								$_SESSION['user'] = Crypt::encryptAlpha($row->id, 6);
								OutputController::success('You have been successfully logged in');
								OutputController::redirect('users');

							// error messages
							} else OutputController::error('Invalid credentials');
						} else OutputController::error('Invalid credentials');
					} else OutputController::error('Invalid password');
				} else OutputController::error('Invalid username');
			} else OutputController::error('Please enter valid details in all form fields');
		}

		OutputController::view('login');
	}
}