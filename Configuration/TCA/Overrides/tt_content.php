<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$_EXTKEY = 'templavoila';

// unserializing the configuration so we can use it here:
$_EXTCONF = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['templavoila']);

// Adding the new content element, "Flexible Content":
t3lib_div::loadTCA('tt_content');
$tempColumns = array(
	'tx_templavoila_ds' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:tt_content.tx_templavoila_ds',
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
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_to' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:tt_content.tx_templavoila_to',
		'displayCond' => 'FIELD:CType:=:' . $_EXTKEY . '_pi1',
		'config' => Array (
			'type' => 'select',
			'items' => Array (
				Array('',0),
			),
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->templateObjectItemsProcFunc',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'selicon_cols' => 10,
		)
	),
	'tx_templavoila_flex' => Array (
		'l10n_cat' => 'text',
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:tt_content.tx_templavoila_flex',
		'displayCond' => 'FIELD:tx_templavoila_ds:REQ:true',
		'config' => Array (
			'type' => 'flex',
			'ds_pointerField' => 'tx_templavoila_ds',
			'ds_tableField' => 'tx_templavoila_datastructure:dataprot',
		)
	),
	'tx_templavoila_pito' => Array (
		'exclude' => 1,
		'label' => 'LLL:EXT:templavoila/locallang_db.xml:tt_content.tx_templavoila_pito',
		'config' => Array (
			'type' => 'select',
			'items' => Array (
				Array('',0),
			),
			'itemsProcFunc' => 'tx_templavoila_handleStaticdatastructures->pi_templates',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'selicon_cols' => 10,
		)
	),
);
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);

$TCA['tt_content']['ctrl']['typeicons'][$_EXTKEY . '_pi1'] = t3lib_extMgm::extRelPath($_EXTKEY) . '/icon_fce_ce.png';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$_EXTKEY . '_pi1'] =  'extensions-templavoila-type-fce';
t3lib_extMgm::addPlugin(array('LLL:EXT:templavoila/locallang_db.xml:tt_content.CType_pi1', $_EXTKEY . '_pi1', 'EXT:' . $_EXTKEY . '/icon_fce_ce.png'), 'CType', $_EXTKEY);

if ($_EXTCONF['enable.']['selectDataStructure']) {
	if ($TCA['tt_content']['ctrl']['requestUpdate'] != '') {
		$TCA['tt_content']['ctrl']['requestUpdate'] .= ',';
	}
	$TCA['tt_content']['ctrl']['requestUpdate'] .= 'tx_templavoila_ds';
}


if(tx_templavoila_div::convertVersionNumberToInteger(TYPO3_version) >= 4005000) {

	$TCA['tt_content']['types'][$_EXTKEY . '_pi1']['showitem'] =
		'--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.headers;headers,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.appearance,
					--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.frames;frames,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.extended';
	if ($_EXTCONF['enable.']['selectDataStructure']) {
		t3lib_extMgm::addToAllTCAtypes('tt_content', 'tx_templavoila_ds;;;;1-1-1,tx_templavoila_to', $_EXTKEY . '_pi1', 'after:layout');
	} else {
		t3lib_extMgm::addToAllTCAtypes('tt_content', 'tx_templavoila_to', $_EXTKEY . '_pi1', 'after:layout');
	}
	t3lib_extMgm::addToAllTCAtypes('tt_content', 'tx_templavoila_flex;;;;1-1-1', $_EXTKEY . '_pi1', 'after:subheader');

} else {
	$TCA['tt_content']['types'][$_EXTKEY . '_pi1']['showitem'] =
		'CType;;4;;1-1-1, hidden, header;;' . (($_EXTCONF['enable.']['renderFCEHeader']) ? '3' : '' ) . ';;2-2-2, linkToTop;;;;3-3-3,
		--div--;LLL:EXT:templavoila/locallang_db.xml:tt_content.CType_pi1,' . (($_EXTCONF['enable.']['selectDataStructure']) ? 'tx_templavoila_ds,' : '') . 'tx_templavoila_to,tx_templavoila_flex;;;;2-2-2,
		--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime, fe_group';
}