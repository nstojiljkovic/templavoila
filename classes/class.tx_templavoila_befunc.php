<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Nikola Stojiljkovic
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

class tx_templavoila_befunc extends t3lib_BEfunc {
	/**
	 * @var bool
	 */
	static $enableFluidTemplateObjects = NULL;

	/**
	 * @return bool|null
	 */
	static function isFluidTemplateObjectsFeatureEnabled() {
		if (self::$enableFluidTemplateObjects===NULL) {
			$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['templavoila']);
			self::$enableFluidTemplateObjects = (bool) $extConfig['staticTO.']['fluidTemplateObjects'];
		}

		return self::$enableFluidTemplateObjects;
	}

	/**
	 * Like getRecord(), but overlays workspace version if any.
	 *
	 * @param string $table Table name present in $GLOBALS['TCA']
	 * @param integer $uid UID of record
	 * @param string $fields List of fields to select
	 * @param string $where Additional WHERE clause, eg. " AND blablabla = 0
	 * @param boolean $useDeleteClause Use the deleteClause to check if a record is deleted (default TRUE)
	 * @param boolean $unsetMovePointers If TRUE the function does not return a "pointer" row for moved records in a workspace
	 * @return array Returns the row if found, otherwise nothing
	 */
	static public function getRecordWSOL($table, $uid, $fields = '*', $where = '', $useDeleteClause = TRUE, $unsetMovePointers = FALSE) {
		if ($table=="tx_templavoila_tmplobj" && self::isFluidTemplateObjectsFeatureEnabled()) {
			$toRepo = t3lib_div::makeInstance('tx_templavoila_templateRepository'); /** @var $toRepo tx_templavoila_templateRepository */
			$to = $toRepo->getTemplateByUid($uid, TRUE); /** @var $to tx_templavoila_template */
			if ($to) {
				return $to->getRow();
			}
		}

		return t3lib_BEfunc::getRecordWSOL($table, $uid, $fields, $where, $useDeleteClause, $unsetMovePointers);
	}

	/**
	 * Gets record with uid = $uid from $table
	 * You can set $field to a list of fields (default is '*')
	 * Additional WHERE clauses can be added by $where (fx. ' AND blabla = 1')
	 * Will automatically check if records has been deleted and if so, not return anything.
	 * $table must be found in $GLOBALS['TCA']
	 *
	 * @param string $table Table name present in $GLOBALS['TCA']
	 * @param integer $uid UID of record
	 * @param string $fields List of fields to select
	 * @param string $where Additional WHERE clause, eg. " AND blablabla = 0
	 * @param boolean $useDeleteClause Use the deleteClause to check if a record is deleted (default TRUE)
	 * @return array Returns the row if found, otherwise nothing
	 */
	static public function getRecord($table, $uid, $fields = '*', $where = '', $useDeleteClause = TRUE) {
		if ($table=="tx_templavoila_tmplobj" && self::isFluidTemplateObjectsFeatureEnabled()) {
			$toRepo = t3lib_div::makeInstance('tx_templavoila_templateRepository'); /** @var $toRepo tx_templavoila_templateRepository */
			$to = $toRepo->getTemplateByUid($uid, TRUE); /** @var $to tx_templavoila_template */
			if ($to) {
				return $to->getRow();
			}
		}

		return t3lib_BEfunc::getRecord($table, $uid, $fields, $where, $useDeleteClause);
	}
}