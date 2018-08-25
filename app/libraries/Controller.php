<?php

defined('_INDEX_EXEC') or die('Restricted access');

/**
 * base controller
 * loads the models and views
 */
abstract class Controller {
	protected $component;

	// load model
	protected function getModel($model = null){
		if (!$model)
			$model[0] = chop(ucwords($this->component), 's');
		else
			$model = explode('/', $model);

		$componentModalPath = APPROOT . '/components/';
		if (isset($model[1])) {
			$componentModalPath .= $model[0] . '/';
			$model[0] = $model[1];
			unset($model[1]);
		} else $componentModalPath .= $this->component . '/';

		if (file_exists($componentModalPath . $model[0] . '.php')) {
			require_once $componentModalPath . $model[0] . '.php';
			return new $model[0]();

		} else Misc::generateErrorPage('Model does not exist');
	}

	// load view
	protected function renderView($view, $data = []){
		$view = explode('/', $view);

		$componentViewsPath = APPROOT . '/components/';
		if (isset($view[1])) {
			$componentViewsPath .= $view[0] . '/views/';
			unset($view[0]);
		} else $componentViewsPath .= $this->component . '/views/';

		if (file_exists($componentViewsPath . $view[0] .  '.php')) {
			if (file_exists($componentViewsPath . 'header.php'))
				require_once $componentViewsPath . 'header.php';
			else require_once APPROOT . '/views/header.php';
			if (file_exists($componentViewsPath . 'navbar.php'))
				require_once $componentViewsPath . 'navbar.php';
			echo '<div id="container">';
			require_once $componentViewsPath . $view[0] . '.php';
			echo '</div>';
			if (file_exists($componentViewsPath . 'footer.php'))
				require_once $componentViewsPath . 'footer.php';
			else require_once APPROOT . '/views/footer.php';

		} else Misc::generateErrorPage('View does not exists');
	}

	// dispatch a function
	public function dispatchMethod($func, $params = []) {
		if (method_exists($this, $func)) {
			if (!is_callable([$this, $func]))
				Misc::generateErrorPage();
			else
				call_user_func_array([$this, $func], $params);

		} else {
			$func = ucwords($func);
			$filePath = APPROOT . '/components/' . $this->component . '/methods/' . $func . '.php';
			if (file_exists($filePath)) {
				require_once $filePath;
				$reflect  = new ReflectionClass($func);
				$reflect->newInstanceArgs($params);

			} else Misc::generateErrorPage('Method does not exist');
		}
	}

	protected function renderForm($form) {
		Forms::render($form, $this->component);
	}

	protected function validateForm($form) {
		return Forms::validate($form, $this->component);
	}
}