<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Response {
	private static $response;

	// function initialize value of output
	public static function init() {
		Response::$response = [
			'messages' => [],
			'redirect' => [
				'valid' => false,
				'link' => ''
			],
			'view' => '',
			'data' => []
		];
	}

	public static function session($data) {
		if (App::get('isAPIRequest'))
			Response::$response['session'] = $data;
	}

	// function to set view
	public static function view($view, $data = null) {
		Response::$response['view'] = $view;
		if ($data !== null)
			Response::data($data);
	}

	// function to set data
	public static function data($data) {
		Response::$response['data'] = $data;
	}

	// function to push a success message
	public static function success($message) {
		array_push(Response::$response['messages'], [
			'type' => 'success',
			'message' => $message
		]);
	}

	// function to push information
	public static function info($message) {
		array_push(Response::$response['messages'], [
			'type' => 'info',
			'message' => $message
		]);
	}

	// function to push an error message
	public static function error($message) {
		array_push(Response::$response['messages'], [
			'type' => 'error',
			'message' => $message
		]);
	}

	// function to terminate processing, and display a fatal error
	public static function fatal($message = 'Invalid URL') {
		if (App::get('isAPIRequest')) {
			Response::$response = ['fatal' => $message];
			Response::renderJSON();
		} else {
			require_once APPROOT . '/views/message.php';
			Session::destroy();
			die();
		}
	}

	// function to redirect or instruct to redirect
	public static function redirect($link = '') {
		Response::$response['view'] = '';
		Response::$response['data'] = [];
		Response::$response['redirect'] = [
			'valid' => true,
			'link' => $link
		];
		Response::render();
	}

	// function to start final rendering of output
	public static function render() {
		if (App::get('isAPIRequest'))
			Response::renderJSON();
		else
			Response::renderHTML();
	}

	// function to render HTML output
	private static function renderHTML() {
		foreach (Response::$response['messages'] as $message)
			Messages::{$message['type']}($message['message']);

		if (Response::$response['redirect']['valid']) {
			header('Location: ' . URLROOT . '/' . Response::$response['redirect']['link']);
			Session::destroy();
			die();
		}

		if (!empty(Response::$response['view'])) {
			Response::$response['view'] = explode('/', Response::$response['view']);
			$componentViewsPath = APPROOT . '/components/';
			if (isset(Response::$response['view'][1])) {
				$componentViewsPath .= Response::$response['view'][0] . '/views/';
				unset(Response::$response['view'][0]);
			} else $componentViewsPath .= strtolower(App::get('component')) . '/views/';
			$data = Response::$response['data'];
			if (file_exists($componentViewsPath . Response::$response['view'][0] .  '.php')) {
				if (file_exists($componentViewsPath . 'header.php'))
					require_once $componentViewsPath . 'header.php';
				else require_once APPROOT . '/views/header.php';
				echo '<div id="container">';
				require_once $componentViewsPath . Response::$response['view'][0] . '.php';
				echo '</div>';
				if (file_exists($componentViewsPath . 'footer.php'))
					require_once $componentViewsPath . 'footer.php';
				else require_once APPROOT . '/views/footer.php';
			} else Response::fatal('View does not exist');
		}
		Session::destroy();
		die();
	}

	// function to render JSON output
	private static function renderJSON() {
		header('Content-type: application/json');
		if (!empty(Response::$response['view'])) {
			$view = explode('/', Response::$response['view']);
			if (isset($view[1]))
				Response::$response['view'] = $view[0] . '/' . $view[1];
			else
				Response::$response['view'] = strtolower(App::get('component')) . '/' . $view[0];
		}
		Session::destroy();
		echo json_encode(Response::$response);
		die();
	}
}
Response::init();