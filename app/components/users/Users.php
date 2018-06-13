<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Users extends Controller {
	protected $user;

	public function __construct() {
		$this->component = 'Users';
		$this->user = $this->model();
		parent::__construct();
	}

	public function index() {
		if (!Misc::validateLogin()) $this->dispatchMethod('logout');
		$this->view('dashboard');
	}
}