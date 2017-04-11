<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$_EXTKEY = 'templavoila';

if (!isset($GLOBALS['TCA']['tx_templavoila_tmplobj']['ctrl']['type'])) {
	if (file_exists($GLOBALS['TCA']['tx_templavoila_tmplobj']['ctrl']['dynamicConfigFile'])) {
		require($GLOBALS['TCA']['tx_templavoila_tmplobj']['ctrl']['dynamicConfigFile']);
	}
// Adding tables:
	$GLOBALS['TCA']['tx_templavoila_tmplobj'] = Array(
		'ctrl' => Array(
			'title' => 'LLL:EXT:templavoila/locallang_db.xml:tx_templavoila_tmplobj',
			'label' => 'title',
			'label_userFunc' => 'EXT:templavoila/classes/class.tx_templavoila_label.php:&tx_templavoila_label->getLabel',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'sortby' => 'sorting',
			'default_sortby' => 'ORDER BY title',
			'delete' => 'deleted',
			'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
			'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_to.gif',
			'selicon_field' => 'previewicon',
			'selicon_field_path' => 'uploads/tx_templavoila',
			'type' => 'parent', // kept to make sure the user is force to reload the form
			'versioningWS' => TRUE,
			'origUid' => 't3_origuid',
			'shadowColumnsForNewPlaceholders' => 'title,datastructure,rendertype,sys_language_uid,parent,rendertype_ref',
		)
	);
}
t3lib_extMgm::allowTableOnStandardPages('tx_templavoila_tmplobj');
