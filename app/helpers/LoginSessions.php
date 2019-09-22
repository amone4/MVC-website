<?php

defined('_INDEX_EXEC') or die('Restricted access');

class LoginSessions {
	private static $userId;

	public static function init() {
		if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
			$userId = Crypt::decrypt($_SESSION['userId']);
			if (ctype_digit($userId))
				self::$userId = $userId;
		}
	}

	public static function validateLogin() {
		return self::$userId !== null;
	}

	public static function setLoginSession($userId) {
		$_SESSION['userId'] = Crypt::encrypt($userId);
	}

	public static function getLoginSession() {
		return self::$userId;
	}

	public static function unsetLoginSession() {
		unset($_SESSION['userId']);
		self::$userId = null;
	}
}