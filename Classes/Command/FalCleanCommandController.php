<?php
namespace GeorgGrossberger\FalClean\Command;
/*                                                                       *
 * Copyright 2014 Georg Großberger <contact@grossberger-ge.org>          *
 *                                                                       *
 * This is free software; you can redistribute it and/or modify it under *
 * the terms of the MIT- / X11 - License                                 *
 *                                                                       */

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Clean unused files from fileadmin and uploads
 *
 * @license MIT <http://opensource.org/licenses/MIT>
 */
class FalCleanCommandController extends CommandController {

	/**
	 * @inject
	 * @var \GeorgGrossberger\FalClean\Service\CleanService
	 */
	protected $cleaner;

	/**
	 * @inject
	 * @var \GeorgGrossberger\FalClean\Output\ShellOutput
	 */
	protected $output;

	public function initializeObject() {
		$this->cleaner->setOutput($this->output);
	}

	/**
	 * Run the cleaner
	 */
	public function executeCommand() {
		$this->cleaner->purge(FALSE);
	}

	/**
	 * Simulate the actions of the cleaner
	 */
	public function simulateCommand() {
		$this->cleaner->purge(TRUE);
	}
}
