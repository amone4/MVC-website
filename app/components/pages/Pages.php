<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Pages extends Controller {

	public function index() {
		Output::view('home');
	}
}