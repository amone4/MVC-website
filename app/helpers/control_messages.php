<?php

class Node {
	public $message;
	public $messageType;

	/**
	 * constructor method
	 *
	 * @param $message
	 * @param $messageType
	 */
	public function __construct($message, $messageType) {
		$this->message = $message;
		$this->messageType = $messageType;
	}
}

class Messages {
	private $top = 0;
	private $nodes = [];

	/**
	 * method to push a single message
	 *
	 * @param $message      string  message to be enqueued
	 * @param $messageType  boolean type of message; 1 => success; 0 => error
	 */
	public function push($message, $messageType) {
		$this->nodes[$this->top++] = new Node($message, $messageType);
	}

	/**
	 * method to pop out all messages
	 */
	public function pop() {
		for ($key = 0; $key < $this->top; $key++)

			if ($this->nodes[$key]->messageType) echo '
				<div class="alert alert-success in" role="alert" id="alert_success'.$key.'">
					<button type="button" class="close" onclick="document.getElementById(\'alert_success'.$key.'\').hidden = true;">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<strong>Success!</strong> '.$this->nodes[$key]->message.'.
				</div>
			';

			else echo '
				<div class="alert alert-danger in" role="alert" id="alert_danger'.$key.'">
					<button type="button" class="close" onclick="document.getElementById(\'alert_danger'.$key.'\').hidden = true;">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<strong>Warning!</strong> '.$this->nodes[$key]->message.'.
				</div>
			';

		$this->top = 0;
	}
}

$_SESSION['control_message_handler'] = new Messages();

function enqueueErrorMessage($message) {
	$_SESSION['control_message_handler']->push($message, false);
}

function enqueueSuccessMessage($message) {
	$_SESSION['control_message_handler']->push($message, true);
}

function dequeMessages() {
	$_SESSION['control_message_handler']->pop();
}