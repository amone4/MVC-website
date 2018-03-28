<?php

class Users extends Controller {
	private $data;
	private $user;

	public function __construct() {
		$this->data = ['success' => '', 'error' => ''];
		$this->user = $this->model('User');
	}

	public function index() {
		// no need to login, if already logged in
		if (validateLogin()) {
			redirect('');
		}

		// if the login form was submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the fields were filled in
			if (postVars($p, ['email', 'password'])) {

				// sanitizing the form data
				$p = filter_var_array($p, FILTER_SANITIZE_STRING);

				// validating email
				if (validateEmail($p['email'])) {

					// validating credentials
					if ($sno = $this->user->validateCredentials($p)) {

						// storing the login session
						$_SESSION['user'] = $sno;
						redirect('');

					// error messages
					} else {
						enqueueErrorMessage('Invalid credentials');
					}
				} else {
					enqueueErrorMessage('Invalid email ID');
				}
			} else {
				enqueueErrorMessage('Please enter valid details in all form fields');
			}
		}

		// loading the login view
		$this->view('users/login', $this->data);
	}

	public function register() {
		// no need to register, if already logged in
		if (validateLogin()) {
			redirect('');
		}

		// if the login form was submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the fields were filled in
			if (postVars($p, ['email', 'name', 'phone', 'password', 'confirm'])) {

				// sanitizing the form data
				$p = filter_var_array($p, FILTER_SANITIZE_STRING);

				// validating email
				if (validateEmail($p['email'])) {

					// validating phone number
					if (validatePhone($p['phone'])) {

						// checking if the passwords match
						if ($p['confirm'] === $p['password']) {

							// checking if email is registered
							if (!$this->user->emailExists($p['email'])) {

								// checking if phone number is registered
								if (!$this->user->phoneExists($p['phone'])) {

									// hashing the password
									$p['password'] = password_hash($p['password'], PASSWORD_DEFAULT);

									// registering the user
									if ($sno = $this->user->register($p)) {

										// generating a confirmation code
										$code = rand(10000, 99999) . '' . $sno . '' . rand(10000, 99999);
										$code = alphaID($code, false, false, PASS);

										// preparing the email message
										$message = 'Click on the following link to confirm your email. <br>' . URLROOT . '/users/confirm_email/' . $code;

										// mailing the confirmation code
										if (mail($p['email'], 'Email Confirmation', $message, 'From: noreply@amone.apps19.com')) {

											// redirecting towards login page with a confirmation message
											enqueueSuccessMessage('Complete your registration by confirming your email');
											redirect('users');

										// error messages
										} else {
											enqueueErrorMessage('Some error occurred while sending you the confirmation email');
										}
									} else {
										enqueueErrorMessage('Some error occurred while registering you');
									}
								} else {
									enqueueErrorMessage('Phone number already registered');
								}
							} else {
								enqueueErrorMessage('Email already registered');
							}
						} else {
							enqueueErrorMessage('Passwords don\'t match');
						}
					} else {
						enqueueErrorMessage('Invalid phone number');
					}
				} else {
					enqueueErrorMessage('Invalid email ID');
				}
			} else {
				enqueueErrorMessage('Please enter valid details in all form fields');
			}
		}

		// loading the registration view
		$this->view('users/register', $this->data);
	}

	public function logout() {
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
			enqueueSuccessMessage('You were successfully logged out');
		}
		redirect('users');
	}

	public function confirm_email($param) {
		// sanitizing the code
		$param = filter_var($param, FILTER_SANITIZE_STRING);

		// checking if the code comprises of valid characters
		if (preg_match('%^[A-Za-z0-9]+$%', $param)) {

			// retrieving the serial number from the code
			$sno = substr(substr(number_format(alphaID($param, true, false, PASS), 0, '', ''), 5), 0, -5);

			// checking if serial number exists
			if ($this->user->serialNumberExists($sno)) {

				// confirming email
				if ($this->user->confirmEmail($sno)) {

					// storing a success message and redirecting
					if (isset($_SESSION['user'])) {
						unset($_SESSION['user']);
					}
					enqueueSuccessMessage('Email ID confirmed. Login to continue');
					redirect('users');

				// error messages
				} else {
					die('Some error occurred while confirming email. Try again');
				}
			} else {
				die('Invalid URL');
			}
		} else {
			die('Invalid URL');
		}
	}

	public function change() {
		// validating if the user is logged in
		if (!validateLogin()) {
			$this->logout();
		}

		// checking if the form was submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the form fields were filled
			if (postVars($p, ['old', 'new', 're'])) {

				// sanitizing strings
				$p = filter_var_array($p, FILTER_SANITIZE_STRING);

				// checking if the two new passwords match
				if ($p['new'] === $p['re']) {

					// checking if the old password was correct
					if ($this->user->confirmPassword(['sno' => $_SESSION['user'], 'password' => $p['old']])) {

						// hashing the password
						$p['new'] = password_hash($p['new'], PASSWORD_DEFAULT);

						// changing password
						if ($this->user->changePassword(['sno' => $_SESSION['user'], 'password' => $p['new']])) {

							// storing a success message and redirecting
							unset($_SESSION['user']);
							enqueueSuccessMessage('Password changed. Login to continue');
							redirect('users');

						// error messages
						} else {
							enqueueErrorMessage('Some error occurred while changing password. Try again');
						}
					} else {
						enqueueErrorMessage('The old password is incorrect');
					}
				} else {
					enqueueErrorMessage('The entered passwords don\'t match');
				}
			} else {
				enqueueErrorMessage('Please enter valid details in all form fields');
			}
		}

		// loading the view to change password
		$this->view('users/change_password', $this->data);
	}

	public function forgot() {
		if (validateLogin()) {
			redirect('');
		}

		$this->data['sent'] = false;

		// checking if the email form was submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the email was filled
			if (postVars($p, ['email'])) {

				// sanitizing email
				$p['email'] = filter_var($p['email'], FILTER_SANITIZE_EMAIL);

				// validating email
				if (validateEmail($p['email'])) {

					// checking if email exists
					if ($sno = $this->user->emailExists($p['email'])) {

						// generating a confirmation code
						try {
							$code = random_int(10000, 99999) . '' . $sno . '' . random_int(10000, 99999);
						} catch (Exception $e) {
							$code = rand(10000, 99999) . '' . $sno . '' . rand(10000, 99999);
						}
						$code = alphaID($code, false, false, PASS);

						// storing the code in the database
						if ($this->user->storeResetCode($sno, $code)) {

							// preparing the email message
							$message = 'Click on the following link to change your password. <br>' . URLROOT . '/users/reset/' . $code;

							// mailing the confirmation code
							if (mail($p['email'], 'Change Password', $message)) {

								// setting a confirmation message
								$this->data['success'] = 'Change your password using the link sent to your email';
								$this->data['sent'] = true;

							// error messages
							} else {
								enqueueErrorMessage('Some error occurred while sending you the code');
							}
						} else {
							enqueueErrorMessage('Some error occurred while processing your reset code');
						}
					} else {
						enqueueErrorMessage('The email is not registered');
					}
				} else {
					$this->data['email'] = 'Invalid email ID';
				}
			} else {
				enqueueErrorMessage('Please enter valid details in all form fields');
			}
		}

		// loading the view
		$this->view('users/forgot_password', $this->data);
	}

	public function reset($param) {
		if (validateLogin()) {
			redirect('');
		}

		// sanitizing the code
		$param = filter_var($param, FILTER_SANITIZE_STRING);

		// checking if the code comprises of valid characters
		if (preg_match('%^[A-Za-z0-9]+$%', $param)) {

			// retrieving the serial number from the code
			$sno = substr(substr(number_format(alphaID($param, true, false, PASS), 0, '', ''), 5), 0, -5);

			// checking if the code is valid
			if ($this->user->verifyResetCode($sno, $param)) {

				$this->data['code'] = $param;

				// checking if the form was submitted
				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

					// checking if the form fields were filled
					if (postVars($p, ['new', 're'])) {

						// sanitizing strings
						$p = filter_var_array($p, FILTER_SANITIZE_STRING);

						// checking if the two new passwords match
						if ($p['new'] === $p['re']) {

							// hashing the password
							$p['new'] = password_hash($p['new'], PASSWORD_DEFAULT);

							// changing password
							if ($this->user->resetPassword(['sno' => $sno, 'password' => $p['new']])) {

								// storing a success message and redirecting
								unset($_SESSION['user']);
								enqueueSuccessMessage('Password changed. Login to continue');
								redirect('users');

							// error messages
							} else {
								enqueueErrorMessage('Some error occurred while changing password. Try again');
							}
						} else {
							enqueueErrorMessage('The entered passwords don\'t match');
						}
					} else {
						enqueueErrorMessage('Please enter valid details in all form fields');
					}
				}

				// loading the view to reset password
				$this->view('users/reset_password', $this->data);

			} else {
				die('Invalid URL');
			}
		} else {
			die('Invalid URL');
		}
	}

	public function createOTP() {
		if (validateLogin()) {
			redirect('');
		}

		// form has been submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the number was entered
			if (postVars($p, ['phone'])) {

				// validating phone number
				if (validatePhone($p['phone'])) {

					// checking if phone number exists
					if ($sno = $this->user->phoneExists($p['phone'])) {

						// preparing the OTP
						$otp = hash('sha256', (time() + $p['phone'] + $sno));
						$otp = substr($otp, 0, 6);
						$otp = strtoupper($otp);

						// storing the otp
						if ($this->user->storeOTP(['sno' => $sno, 'otp' => $otp])) {

							// sending the otp
							if (sendOTP(['phone' => $p['phone'], 'otp' => $otp])) {

								redirect('users/verifyOTP/' . $p['phone']);

							// error messages
							} else {
								enqueueErrorMessage('Some error occurred while sending you the OTP');
							}
						} else {
							enqueueErrorMessage('Some error occurred while communicating with the database');
						}
					} else {
						enqueueErrorMessage('Phone number is not registered');
					}
				} else {
					enqueueErrorMessage('Invalid phone number');
				}
			} else {
				enqueueErrorMessage('Please enter valid details in all form fields');
			}
		}

		// loading the view
		$this->data['otp'] = true;
		$this->view('users/login', $this->data);
	}

	public function verifyOTP($phone) {
		if (validateLogin()) {
			redirect('');
		}

		// validating phone number
		if (validatePhone($phone)) {

			// validating if phone number is registered
			if ($sno = $this->user->phoneExists($phone)) {

				// checking if form was submitted
				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

					// checking if OTP was entered
					if (postVars($p, ['otp'])) {

						// sanitizing OTP
						$p['otp'] = strtoupper(filter_var($p['otp'], FILTER_SANITIZE_STRING));

						// fetching the OTP
						if ($otp = $this->user->getOTP($sno)) {

							// comparing the OTP
							if ($otp === $p['otp']) {

								// resetting the OTP
								$this->user->resetOTP($sno);
								// storing the login session
								$_SESSION['user'] = $sno;
								redirect('');

							// error messages
							} else {
								enqueueErrorMessage('Wrong OTP');
							}
						} else {
							die('OTP has expired');
						}
					} else {
						enqueueErrorMessage('Please enter valid details in all form fields');
					}
				}

				// loading the view
				$this->data['phone'] = $phone;
				$this->view('users/verify_otp', $this->data);

			} else {
				die('Invalid URL');
			}
		} else {
			die('Invalid URL');
		}
	}
}