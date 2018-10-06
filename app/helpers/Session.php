<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Session {
	private static $vars;

	public static function create() {
		Session::$vars = [];
		if (App::get('isAPIRequest') && isset($_POST['session'])) {
			foreach ($_POST['session'] as $key => $value) {
				$key = trim(filter_var($key, FILTER_SANITIZE_STRING));
				array_push(Session::$vars, $key);
				$_SESSION[$key] = trim(filter_var($value, FILTER_SANITIZE_STRING));
			}
			unset($_POST['session']);
		}
	}

	public static function destroy() {
		if (App::get('isAPIRequest')) {
			Response::session($_SESSION);
			session_destroy();
		} else
			foreach (Session::$vars as $key)
				if (isset($_SESSION[$key]))
					unset($_SESSION[$key]);
	}
}