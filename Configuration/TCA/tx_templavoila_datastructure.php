<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$_EXTKEY = 'templavoila';

if (!isset($GLOBALS['TCA']['tx_templavoila_datastructure']['ctrl']['type'])) {
	if (file_exists($GLOBALS['TCA']['tx_templavoila_datastructure']['ctrl']['dynamicConfigFile'])) {
		require($GLOBALS['TCA']['tx_templavoila_datastructure']['ctrl']['dynamicConfigFile']);
	}
	$GLOBALS['TCA']['tx_templavoila_datastructure'] = Array(
		'ctrl' => Array(
			'title' => 'LLL:EXT:templavoila/locallang_db.xml:tx_templavoila_datastructure',
			'label' => 'title',
			'label_userFunc' => 'EXT:templavoila/classes/class.tx_templavoila_label.php:&tx_templavoila_label->getLabel',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'sortby' => 'sorting',
			'default_sortby' => 'ORDER BY title',
			'delete' => 'deleted',
			'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
			'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_ds.gif',
			'selicon_field' => 'previewicon',
			'selicon_field_path' => 'uploads/tx_templavoila',
			'versioningWS' => TRUE,
			'origUid' => 't3_origuid',
			'shadowColumnsForNewPlaceholders' => 'scope,title',
		)
	);
}
t3lib_extMgm::allowTableOnStandardPages('tx_templavoila_datastructure');