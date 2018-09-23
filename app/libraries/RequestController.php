<?php

defined('_INDEX_EXEC') or die('Restricted access');

class RequestController {

	public function __construct(&$method, &$params) {
		$requestURL = $this->setURL();

		if (isset($requestURL[0]) && !empty($requestURL[0])) {
			$componentPath = APPROOT . '/components/' . $requestURL[0];
			App::set('component', ucwords($requestURL[0]));
			unset($requestURL[0]);
		} else {
			$componentPath = APPROOT . '/components/pages';
			App::set('component', 'Pages');
		}

		if (!file_exists($componentPath . '/' . App::get('component') . '.php'))
			OutputController::fatal();

		// require the controller
		require_once $componentPath . '/' . App::get('component') . '.php';

		// check for second part of url
		if (isset($requestURL[1])) {
			$method = $requestURL[1];
			unset($requestURL[1]);
		} else $method = 'index';

		// getting all params
		$params = $requestURL ? array_values($requestURL) : [];
	}

	// function to convert URL into array
	private function setURL() {
		if (isset($_GET['url'])) {
			$requestURL = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
			$this->checkForAPIRequest($requestURL);
			return explode('/', $requestURL);
		} else return [];
	}

	private function checkForAPIRequest(&$requestURL) {
		if (substr($requestURL, 0, 3) === 'api') {
			if (!isset($requestURL[3])) {
				App::set('isAPIRequest', true);
				$requestURL = '';
			} elseif ($requestURL[3] === '/') {
				App::set('isAPIRequest', true);
				$requestURL = substr($requestURL, 4);
			} else App::set('isAPIRequest', false);
		}
	}
}