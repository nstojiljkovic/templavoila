<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$_EXTKEY = 'templavoila';

// unserializing the configuration so we can use it here:
$_EXTCONF = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['templavoila']);

// Fix misbehaviour of the 6.x autoloader
if (!class_exists('tx_templavoila_div')) {
	require_once(t3lib_extMgm::extPath($_EXTKEY) . 'classes/class.tx_templavoila_div.php');
}

// For pages:
$tempColumns = array (
	'tx_templavoila_ds' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:pages.tx_templavoila_ds',
		'config' => array (
			'type' => 'select',
			'items' => Array (
				array('',0),
			),
			'allowNonIdValues' => 1,
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->dataSourceItemsProcFunc',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'suppress_icons' => 'ONLY_SELECTED',
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_to' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:pages.tx_templavoila_to',
		'displayCond' => 'FIELD:tx_templavoila_ds:REQ:true',
		'config' => Array (
			'type' => 'select',
			'items' => Array (
				Array('',0),
			),
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->templateObjectItemsProcFunc',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'suppress_icons' => 'ONLY_SELECTED',
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_next_ds' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:pages.tx_templavoila_next_ds',
		'config' => Array (
			'type' => 'select',
			'items' => Array (
				Array('',0),
			),
			'allowNonIdValues' => 1,
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->dataSourceItemsProcFunc',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'suppress_icons' => 'ONLY_SELECTED',
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_next_to' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:pages.tx_templavoila_next_to',
		'displayCond' => 'FIELD:tx_templavoila_next_ds:REQ:true',
		'config' => Array (
			'type' => 'select',
			'items' => Array (
				Array('',0),
			),
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->templateObjectItemsProcFunc',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'suppress_icons' => 'ONLY_SELECTED',
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_flex' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:pages.tx_templavoila_flex',
		'config' => Array (
			'type' => 'flex',
			'ds_pointerField' => 'tx_templavoila_ds',
			'ds_pointerField_searchParent' => 'pid',
			'ds_pointerField_searchParent_subField' => 'tx_templavoila_next_ds',
			'ds_tableField' => 'tx_templavoila_datastructure:dataprot',
		)
	),
);
t3lib_extMgm::addTCAcolumns('pages', $tempColumns, 1);
if ($_EXTCONF['enable.']['selectDataStructure']) {

	if(tx_templavoila_div::convertVersionNumberToInteger(TYPO3_version) >= 4005000) {
		t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_ds;;;;1-1-1,tx_templavoila_to', '', 'replace:backend_layout');
		t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_next_ds;;;;1-1-1,tx_templavoila_next_to', '', 'replace:backend_layout_next_level');
		t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_flex;;;;1-1-1', '', 'after:title');
	} else {
		t3lib_extMgm::addToAllTCAtypes('pages','tx_templavoila_ds;;;;1-1-1,tx_templavoila_to,tx_templavoila_next_ds;;;;1-1-1,tx_templavoila_next_to,tx_templavoila_flex;;;;1-1-1');
	}

	if ($GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] != '') {
		$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= ',';
	}
	$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= 'tx_templavoila_ds,tx_templavoila_next_ds';

} else {
	if(tx_templavoila_div::convertVersionNumberToInteger(TYPO3_version) >= 4005000) {
		if (!$_EXTCONF['enable.']['oldPageModule']) {
			t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_to;;;;1-1-1', '', 'replace:backend_layout');
			t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_next_to;;;;1-1-1', '', 'replace:backend_layout_next_level');
			t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_flex;;;;1-1-1', '', 'after:title');
		} else {
			t3lib_extMgm::addFieldsToPalette('pages', 'layout', '--linebreak--, tx_templavoila_to;;;;1-1-1, tx_templavoila_next_to;;;;1-1-1', 'after:backend_layout_next_level');
			t3lib_extMgm::addToAllTCAtypes('pages', 'tx_templavoila_flex;;;;1-1-1', '', 'after:title');
		}
	} else {
		t3lib_extMgm::addToAllTCAtypes('pages','tx_templavoila_to;;;;1-1-1,tx_templavoila_next_to;;;;1-1-1,tx_templavoila_flex;;;;1-1-1');
	}

	unset($GLOBALS['TCA']['pages']['columns']['tx_templavoila_to']['displayCond']);
	unset($GLOBALS['TCA']['pages']['columns']['tx_templavoila_next_to']['displayCond']);
}

unset($tempColumns);
