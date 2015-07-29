<?php
namespace GeorgGrossberger\FalClean\Output;
/*                                                                       *
 * Copyright 2014 Georg Großberger <contact@grossberger-ge.org>          *
 *                                                                       *
 * This is free software; you can redistribute it and/or modify it under *
 * the terms of the MIT- / X11 - License                                 *
 *                                                                       */

use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

class ShellOutput implements OutputInterface {

	/**
	 * @var ConsoleOutput
	 */
	private $writer;

	public function __construct() {
		$this->writer = new ConsoleOutput();
	}

	public function info($message, array $arguments = array()) {
		$this->writer->output($message, $arguments);
	}

	public function error($message, array $arguments = array()) {
		$this->writer->output("ERROR: $message", $arguments);
		exit(127);
	}
}
