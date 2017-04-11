<?php
# TYPO3 CVS ID: $Id$
if (!defined ('TYPO3_MODE'))  die ('Access denied.');

// unserializing the configuration so we can use it here:
$_EXTCONF = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['templavoila']);

	// Fix misbehaviour of the 6.x autoloader
if (!class_exists('tx_templavoila_div')) {
	require_once(t3lib_extMgm::extPath($_EXTKEY) . 'classes/class.tx_templavoila_div.php');
}

if (TYPO3_MODE=='BE') {

		// Adding click menu item:
	$GLOBALS['TBE_MODULES_EXT']['xMOD_alt_clickmenu']['extendCMclasses'][] = array(
		'name' => 'tx_templavoila_cm1',
		'path' => t3lib_extMgm::extPath($_EXTKEY).'class.tx_templavoila_cm1.php'
	);
	include_once(t3lib_extMgm::extPath('templavoila').'class.tx_templavoila_handlestaticdatastructures.php');

		// Adding backend modules:
	t3lib_extMgm::addModule('web','txtemplavoilaM1','top',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
	t3lib_extMgm::addModule('web','txtemplavoilaM2','',t3lib_extMgm::extPath($_EXTKEY).'mod2/');

		// Remove default Page module (layout) manually if wanted:
	if (!$_EXTCONF['enable.']['oldPageModule']) {
		$tmp = $GLOBALS['TBE_MODULES']['web'];
		$GLOBALS['TBE_MODULES']['web'] = str_replace (',,',',',str_replace ('layout','',$tmp));
		unset ($GLOBALS['TBE_MODULES']['_PATHS']['web_layout']);
	}

		// Registering CSH:
	t3lib_extMgm::addLLrefForTCAdescr('be_groups','EXT:templavoila/locallang_csh_begr.xml');
	t3lib_extMgm::addLLrefForTCAdescr('pages','EXT:templavoila/locallang_csh_pages.xml');
	t3lib_extMgm::addLLrefForTCAdescr('tt_content','EXT:templavoila/locallang_csh_ttc.xml');
	t3lib_extMgm::addLLrefForTCAdescr('tx_templavoila_datastructure','EXT:templavoila/locallang_csh_ds.xml');
	t3lib_extMgm::addLLrefForTCAdescr('tx_templavoila_tmplobj','EXT:templavoila/locallang_csh_to.xml');
	t3lib_extMgm::addLLrefForTCAdescr('xMOD_tx_templavoila','EXT:templavoila/locallang_csh_module.xml');
	t3lib_extMgm::addLLrefForTCAdescr('xEXT_templavoila','EXT:templavoila/locallang_csh_intro.xml');
	t3lib_extMgm::addLLrefForTCAdescr('_MOD_web_txtemplavoilaM1','EXT:templavoila/locallang_csh_pm.xml');


	t3lib_extMgm::insertModuleFunction(
		'tools_txextdevevalM1',
		'tx_templavoila_extdeveval',
		t3lib_extMgm::extPath($_EXTKEY).'class.tx_templavoila_extdeveval.php',
		'TemplaVoila L10N Mode Conversion Tool'
	);
}

	// Configure the referencing wizard to be used in the web_func module:
if (TYPO3_MODE=='BE')	{
	t3lib_extMgm::insertModuleFunction(
		'web_func',
		'tx_templavoila_referenceElementsWizard',
		t3lib_extMgm::extPath($_EXTKEY).'func_wizards/class.tx_templavoila_referenceelementswizard.php',
		'LLL:EXT:templavoila/locallang.xml:wiz_refElements',
		'wiz'
	);
	t3lib_extMgm::insertModuleFunction(
		'web_func',
		'tx_templavoila_renameFieldInPageFlexWizard',
		t3lib_extMgm::extPath($_EXTKEY).'func_wizards/class.tx_templavoila_renamefieldinpageflexwizard.php',
		'LLL:EXT:templavoila/locallang.xml:wiz_renameFieldsInPage',
		'wiz'
	);
	t3lib_extMgm::addLLrefForTCAdescr('_MOD_web_func','EXT:wizard_crpages/locallang_csh.xml');
}
	// complex condition to make sure the icons are available during frontend editing...
if (TYPO3_MODE == 'BE' ||
	(TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER']) && method_exists($GLOBALS['BE_USER'], 'isFrontendEditingActive')  && $GLOBALS['BE_USER']->isFrontendEditingActive())
) {
	$icons = array(
		'paste' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/clip_pasteafter.gif',
		'pasteSubRef' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/clip_pastesubref.gif',
		'makelocalcopy' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/makelocalcopy.gif',
		'clip_ref' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/clip_ref.gif',
		'clip_ref-release' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/clip_ref_h.gif',
		'unlink' => t3lib_extMgm::extRelPath('templavoila') . 'mod1/unlink.png',
		'htmlvalidate' => t3lib_extMgm::extRelPath('templavoila') . 'resources/icons/html_go.png',
		'type-fce' => t3lib_extMgm::extRelPath('templavoila') . 'icon_fce_ce.png'
	);
	t3lib_SpriteManager::addSingleIcons($icons, $_EXTKEY);
}
