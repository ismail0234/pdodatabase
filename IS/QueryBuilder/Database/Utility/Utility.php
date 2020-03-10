<?php 

namespace IS\QueryBuilder\Database\Utility;

Class Utility
{

	/**
	 *
	 * İzin verilen sql sytnaxlar
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $syntaxValues
	 *
	 */
    public static $syntaxValues = array('=', '!=', '<', '<>' , '>', '<=', '>=');

	/**
	 *
	 * INNER için kabul edilebilen değerler.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $joinValues
	 *
	 */
	public static $joinValues = array('INNER', 'LEFT', 'RIGHT', 'LEFT OUTER', 'RIGHT OUTER');    

	/**
	 *
	 * Order by için kabul edilebilen değerler 
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $orderbyValues
	 *
	 */
	public static $orderbyValues = array('ASC', 'DESC');

	/**
	 *
	 * AND ve OR Değerlerini barındırır.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @var array $andOrValues
	 *
	 */
	public static $andOrValues = array('AND', 'OR');

	/**
	 *
	 * Veritabanı bağlantısı kurabilmek için verilen bilgileri temiz tutar 
	 * ve gerekli kontrolleri sağlar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $configuration
	 * @return array $configuration
	 *
	 */
	public static function inializeConfiguration($configuration)
	{

		if (!isset($configuration['ip'])) {
			$configuration['ip'] = 'localhost';
		}else{
			$configuration['ip'] = trim($configuration['ip']);
		}

		if (!isset($configuration['dbengine'])) {
			$configuration['dbengine'] = 'mysql';
		}else{
			$configuration['dbengine'] = trim($configuration['dbengine']);
		}

		if (!isset($configuration['charset'])) {
			$configuration['charset'] = 'utf8';
		}else{
			$configuration['charset'] = trim($configuration['charset']);
		}

		if (!isset($configuration['persistent'])) {
			$configuration['persistent'] = 0;
		}else{
			$configuration['persistent'] = intval($configuration['persistent']);
		}

		if (isset($configuration['querylog']) && $configuration['querylog'] === true) {
			$configuration['querylog'] = true;
		}else{
			$configuration['querylog'] = false;
		}

		if (isset($configuration['exception']) && $configuration['exception'] === false) {
			$configuration['exception'] = false;
		}else{
			$configuration['exception'] = true;
		}

		if (!isset($configuration['password'])) {
			$configuration['password'] = '';
		}

		if (!isset($configuration['prefix'])) {
			$configuration['prefix'] = '';
		}

		if (!isset($configuration['database']) || empty($configuration['database'])) {
			throw new \Exception("Database name not found.");
		}

		if (!isset($configuration['username']) || empty($configuration['username'])) {
			throw new \Exception("Database username not found.");
		}

		$configuration['database'] = trim($configuration['database']);
		$configuration['username'] = trim($configuration['username']);
		$configuration['password'] = trim($configuration['password']);
		$configuration['prefix']   = trim($configuration['prefix']);
		return $configuration;
	}

	/**
	 *
	 * Veritabanı bağlantı metnini döner.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $config
	 * @return string 
	 *
	 */
	public static function connectionString($config)
	{

		return sprintf("%s:host=%s;dbname=%s;charset=%s", $config['dbengine'], $config['ip'], $config['database'], $config['charset']);
	}

	/**
	 *
	 * String bir değeri virgül ile parçalar ve dizi olarak döner
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string|array $selectData
	 * @return array
	 *
	 */
    public static function stringToArray($selectData)
    {

    	if (is_string($selectData)) {
    		$selectData = explode(',', $selectData);
    	}

    	return $selectData;
    }

    /**
     *
     * Verilen veriyi uygun kolon formatına dönüştürür.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return array
     *
     */
	public static function selectDefaultValue($select, $alias)
	{

		$alias = trim($alias);
		if (!empty($alias)) {
			return array($select => $alias);
		}

		if (empty($alias) && is_string($select)) {
			return array($select => $select);
		}

		return array();
	}

	/**
	 *
	 * Bir değeri dizi olarak düzenler ve verilere tablo ön ekini ekler.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string or array $table
	 * @param string $prefix
	 * @return array
	 *
	 */
    public static function stringArrayPrefixList($tables, $prefix)
    {

    	if (!is_array($tables)) {
    		$tables = array($tables);
    	}

    	$tableList = array();
    	foreach ($tables as $table) 
    	{
    		$table = trim($table);
    		if (!empty($table)) {
    			$tableList[] = $prefix . $table;
    		}
    	}

    	return $tableList;

    }


    /**
     *
     * Where sorgusunda hatalı kalan AND veya OR metnini siler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $where
     * @return string 
     *
     */
    public static function clearAndOr($where)
    {

		$minimum = min(array_keys($where));
		$select  = strtoupper(trim($where[$minimum]));

        if(in_array($select, self::$andOrValues)){
            unset($where[$minimum]);
        }

        return $where;
    }

	/**
	 *
	 * Tablo Ön ekini sorguya ekler.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $name
	 * @param string $prefix
	 * @return string
	 *
	 */
	public static function addPrefix($name, $prefix)
    {

        return preg_replace('@(\w+)\.([a-z\*])@si', $prefix . '$0', $name);
    }

	/**
	 *
	 * Alias ekini döner.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $table
	 * @return string
	 *
	 */
	public static function getAlias($table)
    {

        $alias = explode('as', $table);
        if (isset($alias[1])) 
        {
            $alias = trim($alias[1]);
            if (!empty($alias)) {
                return $alias;
            }
        }
        
        return false;
    }

	/**
	 *
	 * Like için metin üzerindeki özel karakterleri temizelr.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $text
	 * @return string 
	 *
	 */
    public static function likeEscape($text)
    {
    	return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $text);
    }

}