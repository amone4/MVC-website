<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Users extends ComponentController {
	protected $model;

	public function __construct() {
		$this->model = $this->getModel();
	}

	public function index() {
		if (!Misc::validateLogin())
			App::dispatchMethod('logout');
		OutputController::view('dashboard');
	}
}