<?php 

namespace IS\QueryBuilder\Database\Builder;

Class Special
{

	/**
	 *
	 * Özel veritabanı sorgularını çalıştırır.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param Class $db
	 * @param string $query
	 * @param string $table
	 * @param boolean $multiple
	 * @param int $executeType
	 * @return string 
	 *
	 */
	public static function tableCommand($db, $query, $table, $multiple = true, $executeType = 1)
	{

		$tableList = Utility::stringArrayPrefixList($table, $db->prefix);
		if (count($tableList) <= 0) {
			return false;
		}

		if (!$multiple) {
			$tableList = array($tableList[0]);
		}

       	return $db->execute($query . ' ' . implode(', ', $tableList), array(), $executeType);

	}

}