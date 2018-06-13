<?php

defined('_INDEX_EXEC') or die('Restricted access');

/**
 * base controller
 * loads the models and views
 */
abstract class Controller {
	protected $component;

	protected function __construct() {
		if (!isset($this->component) || empty($this->component))
			$this->component = strtolower(get_called_class());
	}

	// load model
	protected function model($model = null){
		if (!$model)
			$model[0] = chop($this->component, 's');

		$componentModalPath = APPROOT . '/components/';
		if (isset($model[1])) {
			$componentModalPath .= $model[0] . '/';
			unset($model[0]);
		} else $componentModalPath .= $this->component . '/';

		if (file_exists($componentModalPath . $model[0] .  '.php')) {
			require_once $componentModalPath . $model[0] . '.php';
			return new $model[0]();

		} else Misc::generateErrorPage('Modal does not exists');
	}

	// load view
	protected function view($view, $data = []){
		$view = explode('/', $view);

		$componentViewsPath = APPROOT . '/components/';
		if (isset($view[1])) {
			$componentViewsPath .= $view[0] . '/views/';
			unset($view[0]);
		} else $componentViewsPath .= $this->component . '/views/';

		if (file_exists($componentViewsPath . $view[0] .  '.php')) {
			require_once APPROOT . '/views/header.php';
			if (file_exists($componentViewsPath . 'navbar.php'))
				require_once $componentViewsPath . 'navbar.php';
			echo '<div id="container">';
			require_once $componentViewsPath . $view[0] . '.php';
			echo '</div>';
			require_once APPROOT . '/views/footer.php';

		} else Misc::generateErrorPage('View does not exists');
	}

	// dispatch a function
	public function dispatchMethod($func, $params = []) {
		$func = ucwords($func);
		$filePath = APPROOT . '/components/' . $this->component . '/methods/' . $func . '.php';
		if (method_exists($this, $func)) {
			try {
				call_user_func_array([$this, $func], $params);
			} catch (ArgumentCountError $e) {
				Misc::generateErrorPage();
			}
		} elseif (file_exists($filePath)) {
			require_once $filePath;
			try {
				$reflect  = new ReflectionClass($func);
				$instance = $reflect->newInstanceArgs($params);
			} catch (ArgumentCountError $e) {
				Misc::generateErrorPage();
			}
		} else Misc::generateErrorPage('Method does not exist');
	}
}