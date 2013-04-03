<?php
/***************************************************************
* Copyright notice
*
* (c) 2010 Tolleiv Nietsch <tolleiv.nietsch@typo3.org>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class to provide unique access to datastructure
 *
 * @author	Tolleiv Nietsch <tolleiv.nietsch@typo3.org>
 */
class tx_templavoila_templateRepository {

	/**
	 * @var bool
	 */
	protected $enableFluidTemplateObjects = FALSE;

	public function __construct() {
		$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['templavoila']);
		$this->enableFluidTemplateObjects = (bool) $extConfig['staticTO.']['fluidTemplateObjects'];
	}

	/**
	 * Retrieve a single templateobject by uid or xml-file path
	 *
	 * @param integer $uid
	 * @return tx_templavoila_template
	 */
	public function getTemplateByUid($uid, $tsOnly = FALSE) {
		$result = null;

		if ($this->enableFluidTemplateObjects) {
			$tsData = $this->loadTSTOSettings(0);
			foreach($tsData as $key => $templateConfiguration) {
				if ($uid==rtrim($key, '.')) {
					$templateConfiguration['uid'] = rtrim($key, '.');
					$templateConfiguration['type'] = 'static';
					$result = t3lib_div::makeInstance('tx_templavoila_template', $templateConfiguration);
					break;
				}
			}
		}
		if (!$result && !$tsOnly) {
			$row = t3lib_beFunc::getRecordWSOL('tx_templavoila_tmplobj', $uid);
			if ($row) {
				$result = t3lib_div::makeInstance('tx_templavoila_template', $row);
			}
		}

		return $result;
	}

	/**
	 * Retrieve template objects which are related to a specific datastructure
	 *
	 * @param tx_templavoila_datastructure
	 * @param integer $pid
	 * @return array
	 */
	public function getTemplatesByDatastructure(tx_templavoila_datastructure $ds, $storagePid = 0) {
		$toCollection = array();

		if ($this->enableFluidTemplateObjects) {
				// fetch template objects from TypoScript
			$tsData = $this->loadTSTOSettings($storagePid);
			foreach($tsData as $key => $templateConfiguration) {
				if ($ds->getKey()==$templateConfiguration['datastructure']) {
					$templateConfiguration['uid'] = rtrim($key, '.');
					$templateConfiguration['type'] = 'static';
					$toCollection[$templateConfiguration['uid']] = t3lib_div::makeInstance('tx_templavoila_template', $templateConfiguration);
				}
			}
		}

			// fetch template objects from DB
		$toList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
			'tx_templavoila_tmplobj.uid',
			'tx_templavoila_tmplobj',
			'tx_templavoila_tmplobj.datastructure=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($ds->getKey(), 'tx_templavoila_tmplobj')
				. (intval($storagePid) > 0 ? ' AND tx_templavoila_tmplobj.pid = ' . intval($storagePid) : '')
				. t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj')
				. ' AND pid!=-1 '
				. t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmplobj')
		);
		foreach ($toList as $toRec) {
			if (!$toCollection[$toRec['uid']]) {
				$toCollection[$toRec['uid']] = $this->getTemplateByUid($toRec['uid']);
			}
		}
		usort($toCollection, array($this, 'sortTemplates'));
		return $toCollection;
	}

	/**
	 * Retrieve template objects with a certain scope within the given storage folder
	 *
	 * @param integer $pid
	 * @param integer $scope
	 * @return array
	 */
	public function getTemplatesByStoragePidAndScope($storagePid, $scope) {
		$dsRepo = t3lib_div::makeInstance('tx_templavoila_datastructureRepository');
		$dsList = $dsRepo->getDatastructuresByStoragePidAndScope($storagePid, $scope);
		$toCollection = array();
		foreach($dsList as $dsObj) {
			$toCollection = array_merge($toCollection, $this->getTemplatesByDatastructure($dsObj, $storagePid));
		}
		usort($toCollection, array($this, 'sortTemplates'));
		return $toCollection;
	}

	/**
	 * Retrieve template objects which have a specific template as their parent
	 *
	 * @param tx_templavoila_datastructure
	 * @param integer $pid
	 * @return array
	 */
	public function getTemplatesByParentTemplate(tx_templavoila_template $to, $storagePid=0) {
		$toCollection = array();

		if ($this->enableFluidTemplateObjects) {
				// fetch template objects from TypoScript
			$tsData = $this->loadTSTOSettings($storagePid);
			foreach($tsData as $key => $templateConfiguration) {
				if ($to->getKey()==$templateConfiguration['parent']) {
					$templateConfiguration['uid'] = rtrim($key, '.');
					$templateConfiguration['type'] = 'static';
					$toCollection[$templateConfiguration['uid']] = t3lib_div::makeInstance('tx_templavoila_template', $templateConfiguration);
				}
			}
		}

			// fetch template objects from DB
		$toList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
			'tx_templavoila_tmplobj.uid',
			'tx_templavoila_tmplobj',
			'tx_templavoila_tmplobj.parent=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($to->getKey(), 'tx_templavoila_tmplobj')
				. (intval($storagePid) > 0 ? ' AND tx_templavoila_tmplobj.pid = ' . intval($storagePid) : ' AND pid!=-1')
				. t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj')
				. t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmplobj')
		);
		foreach ($toList as $toRec) {
			if (!$toCollection[$toRec['uid']]) {
				$toCollection[$toRec['uid']] = $this->getTemplateByUid($toRec['uid']);
			}
		}
		usort($toCollection, array($this, 'sortTemplates'));
		return $toCollection;
	}

	/**
	 * Retrieve a collection (array) of tx_templavoila_datastructure objects
	 *
	 * @return array
	 */
	public function getAll($storagePid=0) {
		$toCollection = array();

		if ($this->enableFluidTemplateObjects) {
				// fetch template objects from TypoScript
			$tsData = $this->loadTSTOSettings($storagePid);
			foreach($tsData as $key => $templateConfiguration) {
				$templateConfiguration['uid'] = rtrim($key, '.');
				$templateConfiguration['type'] = 'static';
				$toCollection[$templateConfiguration['uid']] = t3lib_div::makeInstance('tx_templavoila_template', $templateConfiguration);
			}
		}

			// fetch template objects from DB
		$toList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
			'tx_templavoila_tmplobj.uid',
			'tx_templavoila_tmplobj',
			'1=1'
				. (intval($storagePid) > 0 ? ' AND tx_templavoila_tmplobj.pid = ' . intval($storagePid) : ' AND tx_templavoila_tmplobj.pid!=-1')
				. t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj')
				. t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmplobj')
		);
		$toCollection = array();
		foreach ($toList as $toRec) {
			if (!$toCollection[$toRec['uid']]) {
				$toCollection[$toRec['uid']] = $this->getTemplateByUid($toRec['uid']);
			}
		}
		usort($toCollection, array($this, 'sortTemplates'));
		return $toCollection;
	}

	/**
	 * Sorts datastructure alphabetically
	 *
	 * @param	tx_templavoila_template $obj1
	 * @param	tx_templavoila_template $obj2
	 * @return	int	Result of the comparison (see strcmp())
	 * @see	usort()
	 * @see	strcmp()
	 */
	public function sortTemplates($obj1, $obj2) {
		return strcmp(strtolower($obj1->getSortingFieldValue()), strtolower($obj2->getSortingFieldValue()));
	}

	/**
	 * Find all folders with template objects
	 *
	 * @return array
	 */
	public function getTemplateStoragePids() {
		// @todo: implement me
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'pid',
					'tx_templavoila_tmplobj',
					'pid>=0'.t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj'),
					'pid'
				);
		while($res && false !== ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)))	{
			$list[]= $row['pid'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return $list;
	}

	/**
	 *
	 *
	 * @return integer
	 */
	public function getTemplateCountForPid($pid) {
		// @todo: implement me
		$toCnt = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'count(*) as cnt',
					'tx_templavoila_tmplobj',
					'pid=' . intval($pid) .t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj')
				);
		return $toCnt[0]['cnt'];
	}

	/**
	 * @var array
	 */
	protected $tsCache = array();

	/**
	 * Loads the TypoScript for a page
	 *
	 * @param int $pageUid
	 * @return array The TypoScript setup
	 */
	function loadTSTOSettings($pageUid) {
		if (TYPO3_MODE=='FE' && $GLOBALS['TSFE']) {
			return $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_templavoila.']['settings.']['templates.'] ? $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_templavoila.']['settings.']['templates.'] : array();
		} elseif (!$this->tsCache[$pageUid]) {
			if ($pageUid==0) {
				$globalTemplatesConf = array();

				foreach ($this->getAllTypoScriptRecordPids() as $pid) {
					$ts = $this->loadTSFE($pid);
					$tsConfiguration = $ts['plugin.']['tx_templavoila.']['settings.']['templates.'] ? $ts['plugin.']['tx_templavoila.']['settings.']['templates.'] : array();

					foreach($tsConfiguration as $toKey => $toConf) {
						if (!$globalTemplatesConf[$toKey]) {
							$globalTemplatesConf[$toKey] = $toConf;
						} else {
							$diff = $this->array_diff_recursive($globalTemplatesConf[$toKey], $toConf);
							if (count($diff)>0) {
								throw new Tx_IndexerAdapter_Exception_InvalidConfigurationException(sprintf('TypoScript configuration for indexer_adapter configuration "%s" is different on pages with uids %s and %s. This is not allowed!', $toKey, $pid, $uniqueTSRegistrationPids[$toKey]), 9776234);
							}
						}
					}

				}

				$this->tsCache[$pageUid] = $globalTemplatesConf;
			} else {
				$ts = $this->loadTSFE($pageUid);
				$this->tsCache[$pageUid] = $ts['plugin.']['tx_templavoila.']['settings.']['templates.'];
			}
		}

		return $this->tsCache[$pageUid] ? $this->tsCache[$pageUid] : array();
	}

	/**
	 * Filters keys off from first array that also exist in second array. Comparison is done by keys and values.
	 * This method is a recursive version of php array_diff()
	 *
	 * @param array $array1 Source array
	 * @param array $array2 Reduce source array by this array
	 * @return array Source array reduced by keys also present in second array
	 */
	protected function array_diff_recursive(array $array1, array $array2) {

		$differenceArray = array();
		foreach ($array1 as $key => $value) {
			if (!array_key_exists($key, $array2)) {
				$differenceArray[$key] = $value;
			} elseif (is_array($value)) {
				if (is_array($array2[$key])) {
					$diff = self::array_diff_recursive($value, $array2[$key]);
					if (count($diff)>0) {
						$differenceArray[$key] = $diff;
					}
				}
			}
		}
		foreach ($array2 as $key => $value) {
			if (!array_key_exists($key, $differenceArray)) {
				if (!array_key_exists($key, $array1)) {
					$differenceArray[$key] = $value;
				} elseif (is_array($value)) {
					if (is_array($array1[$key])) {
						$diff = self::array_diff_recursive($value, $array1[$key]);
						if (count($diff)>0) {
							$differenceArray[$key] = $diff;
						}
					}
				}
			}
		}

		return $differenceArray;
	}

	/**
	 * Returns an array of all pids where sys_template records are stored
	 *
	 * @return array
	 */
	protected function getAllTypoScriptRecordPids() {
		$result = array();

		$table = 'sys_template';

		$enableFields = t3lib_BEfunc::BEenableFields ( $table );
		if (trim($enableFields) == 'AND') {
			$enableFields = '';
		}
		$enableFields .= t3lib_BEfunc::deleteClause($table);

		// pid > 0 makes sure we don't get any draft workspace records
		$res = $this->getDatabase()->exec_SELECTquery('*','sys_template','pid > 0'.$enableFields);
		while ($res && $row = $this->getDatabase()->sql_fetch_assoc($res)) {
			$result[] = $row['pid'];
		}
		$this->getDatabase()->sql_free_result($res);

		return $result;
	}

	/**
	 * Loads the TypoScript for a page
	 *
	 * @param int $pageUid
	 * @return array The TypoScript setup
	 */
	protected function loadTSFE($pageUid) {
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup;
	}

	/**
	 * Get the database object
	 *
	 * @access protected
	 * @see t3lib_db
	 * @return t3lib_db
	 */
	protected function getDatabase() {
		return $GLOBALS['TYPO3_DB'];
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/templavoila/classes/class.tx_templavoila_templateRepository.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/templavoila/classes/class.tx_templavoila_templateRepository.php']);
}
?>
