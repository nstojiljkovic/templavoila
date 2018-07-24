<?php


$EM_CONF[$_EXTKEY] = array (
	'title' => 'TemplaVoila!',
	'description' => 'Point-and-click, popular and easy template engine for TYPO3. Public free support is provided only through TYPO3 mailing lists! Contact by e-mail for commercial support.',
	'category' => 'misc',
	'shy' => 0,
	'version' => '2.1.2',
	'dependencies' => 'static_info_tables,cms,lang',
	'conflicts' => 'kb_tv_clipboard,templavoila_cw,eu_tradvoila,me_templavoilalayout,me_templavoilalayout2',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'cm1,cm2,mod1,mod2',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_templavoila/',
	'modify_tables' => 'pages,tt_content,be_groups',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Tolleiv Nietsch',
	'author_email' => 'tolleiv.nietsch@typo3.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => 
	array (
	  'depends' => 
	  array (
	    'php' => '5.3.0-0.0.0',
	    'typo3' => '6.2.0-0.0.0',
	    'static_info_tables' => '',
	    'cms' => '',
	    'lang' => '',
	  ),
	  'conflicts' => 
	  array (
	    'kb_tv_clipboard' => '-0.1.0',
	    'templavoila_cw' => '-0.1.0',
	    'eu_tradvoila' => '-0.0.2',
	    'me_templavoilalayout' => '',
	    'me_templavoilalayout2' => '',
	  ),
	  'suggests' => 
	  array (
	  ),
	),
	'suggests' => 
	array (
	),
);

?>