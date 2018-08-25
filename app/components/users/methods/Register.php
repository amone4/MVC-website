<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Register extends Users {

	public function __construct() {
		parent::__construct();

		// checking if the user is logged in
		if (Misc::validateLogin()) {
			Messages::info('You can\'t register, because you\'re logged in');
			$this->dispatchMethod('logout');
		}

		// checking if the form is submitted
		if (Forms::isSubmitted()) {
			// checking if the form fields are filled
			if (Forms::post($p, ['name', 'username', 'email', 'phone', 'password', 'confirmPassword'])) {
				// sanitizing data
				$p = filter_var_array($p, FILTER_SANITIZE_STRING);
				// validating email
				if (Validations::email($p['email'])) {
					// validating username
					if (Validations::username($p['username'])) {
						// validating password
						if (Validations::password($p['password']) && Validations::password($p['confirmPassword'])) {
							// validating phone number
							if (Validations::phone($p['phone'])) {
								// validating name
								if (Validations::name($p['name'])) {
									// checking if the passwords match
									if ($p['password'] === $p['confirmPassword']) {
										$p['name'] = strtolower($p['name']);
										// if the email has already been registered
										$this->model->selectWhere(['email' => $p['email']]);
										if ($this->model->rowCount() === 0) {
											// if the username has already been registered
											$this->model->selectWhere(['username' => $p['username']]);
											if ($this->model->rowCount() === 0) {

												// generating confirmation code for email
												$p['code'] = Crypt::encryptAlpha($this->model->getNewID(), 6);
												// mailing the confirmation code
												$message = '<p>Confirm your email by clicking on the link below<br><a href="' . URLROOT . '/users/confirm/email/' . $p['code'] . '">Confirm email</a></p>';
												if (Misc::writeMessage($message, 'code.txt') || mail($p['email'], 'Confirm your email', $message, 'From: noreply@example.com' . "\r\n")) {

													// inserting record in database
													$p['password'] = password_hash($p['password'], PASSWORD_DEFAULT);
													unset($p['confirmPassword']);
													$p['code_sent_on'] = time();
													if ($this->model->insert($p)) {

														Messages::success('You have been successfully registered. Confirm your email, and login again');
														Misc::redirect('users');

														// error messages
													} else Messages::error('Some error occurred while registering you. Try again to register');
												} else Messages::error('Some error occurred while registering you. Try again to register');
											} else Messages::error('Username has already been registered');
										} else Messages::error('Email has already been registered');
									} else Messages::error('Password don\'t match');
								} else Messages::error('Invalid name');
							} else Messages::error('Invalid phone number');
						} else Messages::error('Invalid password');
					} else Messages::error('Invalid username');
				} else Messages::error('Invalid email');
			} else Messages::error('Please enter valid details in all form fields');
		}

		$this->renderView('register');
	}
}