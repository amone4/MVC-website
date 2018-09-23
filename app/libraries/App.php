<?php

class App {
	private static $data;

	public static function start() {
		App::$data = [];
		new RequestController($method, $params);
		App::dispatchMethod($method, $params);
	}

	public static function get($key) {
		if (isset(App::$data[$key]))
			return App::$data[$key];
		else return null;
	}

	public static function set($key, $value) {
		App::$data[$key] = $value;
	}

	public static function dispatchMethod($func, $params = []) {
		try {
			$controller = App::$data['component'];
			$controller = new $controller;
			if (method_exists($controller, $func)) {
				if (!is_callable([$controller, $func])) OutputController::fatal();
				else call_user_func_array([$controller, $func], $params);

			} else {
				$func = ucwords($func);
				$filePath = APPROOT . '/components/' . App::$data['component'] . '/methods/' . $func . '.php';
				if (file_exists($filePath)) {
					require_once $filePath;
					$reflect = new ReflectionClass($func);
					$reflect->newInstanceArgs($params);

				} else OutputController::fatal('Method does not exist');
			}
			OutputController::render();
		} catch (ArgumentCountError $e) {
			OutputController::fatal();
		}
	}
}