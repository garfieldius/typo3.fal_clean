<?php
namespace GeorgGrossberger\FalClean\Output;
/*                                                                       *
 * Copyright 2014 Georg Großberger <contact@grossberger-ge.org>          *
 *                                                                       *
 * This is free software; you can redistribute it and/or modify it under *
 * the terms of the MIT- / X11 - License                                 *
 *                                                                       */

class HttpOutput implements OutputInterface {

	protected $messages = array();

	protected $errors = array();

	private function makeMessage($message, array $arguments = array()) {
		if (!empty($arguments)) {
			$message = vsprintf($message, $arguments);
		}
		return trim($message);
	}

	public function info($message, array $arguments = array()) {
		$this->messages[] = $this->makeMessage($message, $arguments);
	}

	public function error($message, array $arguments = array()) {
		$this->errors[] = $this->makeMessage($message, $arguments);
	}

	public function getOutput() {
		$result = array(
			'messages' => $this->messages
		);

		if (!empty($this->errors)) {
			$result['errors'] = $this->errors;
		}

		return $result;
	}
}
