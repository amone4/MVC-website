<?php

defined('_INDEX_EXEC') or die('Restricted access');

/**
 * app core class
 * creates URL & loads core controller
 * URL format - /controller/method/params
 */
class Core {
	private $currentComponent = 'Pages';
	private $currentMethod = 'index';
	private $params = [];

	public function __construct() {
		$url = $this->getUrl();

		if (isset($url[0]) && !empty($url[0])) {
			$componentPath = APPROOT . '/components/' . $url[0];
			$this->currentComponent = ucwords($url[0]);
			unset($url[0]);
		} else {
			$componentPath = APPROOT . '/components/pages';
			$this->currentComponent = 'Pages';
		}

		if (!file_exists($componentPath . '/' . $this->currentComponent . '.php'))
			Misc::generateErrorPage();

		// require the controller
		require_once $componentPath . '/' . $this->currentComponent . '.php';

		// instantiate controller class
		$controller = new $this->currentComponent;

		// check for second part of url
		if (isset($url[1])) {
			if ($url[1] != 'dispatchMethod') {
				$this->currentMethod = $url[1];
				unset($url[1]);
			} else Misc::generateErrorPage();
		}

		// get params
		$this->params = $url ? array_values($url) : [];

		// dispatch the method
		$controller->dispatchMethod($this->currentMethod, $this->params);
	}

	// function to convert URL into array
	private function getUrl() {
		if (isset($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			return $url;
		}
		return null;
	}
}