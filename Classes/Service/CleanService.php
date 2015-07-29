<?php
namespace GeorgGrossberger\FalClean\Service;
/*                                                                       *
 * Copyright 2014 Georg Großberger <contact@grossberger-ge.org>          *
 *                                                                       *
 * This is free software; you can redistribute it and/or modify it under *
 * the terms of the MIT- / X11 - License                                 *
 *                                                                       */

use GeorgGrossberger\FalClean\Output\OutputInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use TYPO3\CMS\Core\Database\PreparedStatement;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Cleaner service to be called from CLI or module
 *
 * @license MIT <http://opensource.org/licenses/MIT>
 */
class CleanService implements SingletonInterface {

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	public function __construct() {
		$this->db = $GLOBALS['TYPO3_DB'];
	}

	public function setOutput(OutputInterface $output) {
		$this->output = $output;
	}

	protected function purgeDeleted($table, $simulate) {
		$delete = new PreparedStatement('DELETE FROM ' . $table . ' WHERE uid = ?', $table);
		$res = $this->db->exec_SELECTquery('uid', $table, 'deleted=1');

		$this->output->info('Purge %s of deleted records', array($table));
		while ($row = $this->db->sql_fetch_assoc($res)) {
			if ($simulate) {
				$this->output->info('Would delete %s:%s', array($table, $row['uid']));
			} else {
				$delete->execute(array($row['uid']));
				$this->output->info('Delete %s:%s', array($table, $row['uid']));
			}
		}
	}

	public function purge($simulate) {
		$this->output->info('Purge deleted');
		$this->purgeDeleted('sys_file_reference', $simulate);
		$this->db->exec_DELETEquery('sys_file_reference', 'tablenames = \'\' OR fieldname = \'\'');

		$delete = new PreparedStatement('DELETE FROM sys_file_reference WHERE uid = ?', 'sys_file_reference');

		$this->output->info('Purge references pointing to deleted records');

		$res = $this->db->exec_SELECTquery('*', 'sys_file_reference', '');
		$pageTools = new PageRepository();
		$pageTools->init(FALSE);

		while ($row = $this->db->sql_fetch_assoc($res)) {
			$cnt = $this->db->exec_SELECTcountRows(
				'uid',
				$row['tablenames'],
				'uid = ' . $row['uid_foreign'] . $pageTools->enableFields($row['tablenames'])
			);

			if (!$cnt) {
				if ($simulate) {
					$this->output->info('Would delete reference ' . $row['uid']);
				} else {
					$delete->execute(array($row['uid']));
					$this->output->info('Deleted reference ' . $row['uid']);
				}
			}
		}

		$delete->free();

		$this->output->info('Purge sys_file records with no references');

		$delete = new PreparedStatement('DELETE FROM sys_file WHERE uid = ?', 'sys_file');

		$res = $this->db->exec_SELECTquery(
			'uid',
			'sys_file',
			'uid NOT IN (select uid_local from sys_file_reference group by uid_local)'
		);

		while ($row = $this->db->sql_fetch_assoc($res)) {
			if ($simulate) {
				$this->output->info('Would delete file record %s', array($row['uid']));
			} else {
				$delete->execute(array($row['uid']));
				$this->output->info('Deleted file record <b>%s</b>', array($row['uid']));
			}
		}

		$this->output->info('Purge actual files with no record');

		$prefixRegex = '/^' . preg_quote(PATH_site, '/'). '(fileadmin|uploads)/';
		$files = new \RegexIterator(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					PATH_site,
					RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::UNIX_PATHS
				),
				RecursiveIteratorIterator::LEAVES_ONLY | RecursiveIteratorIterator::CHILD_FIRST
			),
			$prefixRegex
		);

		$exists = new PreparedStatement('SELECT uid FROM sys_file WHERE identifier = ?', 'sys_file');
		$fileSize = 0;

		foreach ($files as $file) {

			$filename = (string) $file;

			if (!is_file($filename)) {
				continue;
			}

			$fileId = preg_replace($prefixRegex, '', $filename);
			$exists->execute(array($fileId));
			$result = $exists->fetchAll();

			if (empty($result[0]['uid'])) {
				$fileSize += filesize($filename);
				if ($simulate) {
					$this->output->info('<i>Would delete file %s</i>', array($filename));
				} else {
					unlink($filename);
					$this->output->info('Delete file %s', array($filename));
				}
			}
		}

		$size = GeneralUtility::formatSize($fileSize);

		if ($simulate) {
			$this->output->info('Would delete %s of files', array($size));
			$this->output->info('Would truncate table sys_file_processedfile');
		} else {
			$this->output->info('Deleted %s of files', array($size));
			$this->db->exec_TRUNCATEquery('sys_file_processedfile');
			$this->output->info('Truncated table sys_file_processedfile');
		}
	}
}
