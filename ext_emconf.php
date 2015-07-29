<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'FAL Cleanup',
	'description' => 'Remove unreferenced files from uploads and fileadmin',
	'category' => 'misc',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Georg Großberger',
	'author_email' => 'contact@grossberger-ge.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.0-7.9.99'
		),
		'conflicts' => array(),
		'suggests' => array()
	),
	'suggests' => array()
);
