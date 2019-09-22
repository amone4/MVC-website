<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Users extends Controller {
	protected $model;

	public function __construct() {
		$this->model = $this->getModel();
	}

	public function index() {
		if (!LoginSessions::validateLogin())
			App::dispatchMethod('logout');
		Response::view('dashboard');
	}
}