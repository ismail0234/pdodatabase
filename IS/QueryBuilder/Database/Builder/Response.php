<?php 

namespace IS\QueryBuilder\Database\Builder;

use IS\QueryBuilder\Database\Utility\Utility;

Class Response
{

    /**
     *
     * Delete işlemini tamamlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param Class $builder
     * @param string $table
     * @return int 
     *
     */
    public static function delete($builder, $table)
    {

        if (empty($table)) {
            return 0;
        }

        $whereCombine = self::whereCombine($builder->sqlData, $builder->prefix);

        $sqlQuery = sprintf('DELETE FROM %s%s %s', $builder->prefix, $table, $whereCombine['query']);
        return $builder->execute($sqlQuery, $whereCombine["value"], 5);     
    }

    /**
     *
     * Where kısmının sql sorgusunu oluşturur.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $sqlData
     * @param string $prefix
     * @return string 
     *
     */
    private static function whereCombine($sqlData, $prefix)
    {
    
        $whereQuery = '';

        if (count($sqlData['where']) > 0) {
            $whereQuery = 'WHERE ' . implode(' ', Utility::clearAndOr($sqlData['where']));
            $whereQuery = preg_replace('@\( AND|\( OR@si', '(', $whereQuery);
            $whereQuery = preg_replace('@AND \)|OR \)@si', ')', $whereQuery);
        }

        if (count($sqlData['groupby']) > 0) {
            $whereQuery .= ' GROUP BY ' . implode(',', $sqlData['groupby'] );
        }

        if (count($sqlData['having']['text']) > 0) {
            $whereQuery .= ' HAVING ' . implode(',', $sqlData['having']['text']);
            $sqlData['value'] = array_merge($sqlData['value'], $sqlData['having']['value']);
        }

        if (count($sqlData['orderby']) > 0) {
            $whereQuery .= ' ORDER BY ' . implode(',', $sqlData['orderby']);
        }

        $whereQuery = Utility::addPrefix($whereQuery, $prefix);

        if (count($sqlData['limit']['text']) > 0) {
            $whereQuery .= ' ' . implode(' ', $sqlData['limit']['text']);
            $sqlData['value'] = array_merge($sqlData['value'], $sqlData['limit']['value']);
        }

        return array('value' => array_values($sqlData['value']), 'query' => $whereQuery);
    } 

    /**
     *
     * SELECT sorgusunun sql raw halini oluşturur ve döner.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param Class $sqlData
     * @param string $prefix
     * @return string 
     *
     */
    private static function selectCombine($sqlData, $prefix)
    {

        $select = 'SELECT ';

        if (count($sqlData['select']) > 0) {
            $select .= implode(',', $sqlData['select']);
        }else{
            $select .= '*';
        }

        $select .= ' FROM '; 

        if(count($sqlData['table']) > 0 ){
            $select .= implode( ',', $sqlData['table']);
        }

        if(count($sqlData['join']) > 0 ){
            $select .= implode(' ', $sqlData['join']);
        }

        $select = Utility::addPrefix($select, $prefix);
        if (count($sqlData['ignoreAlias']) > 0) 
        {
            foreach ($sqlData['ignoreAlias'] as $alias) {
                $select = str_replace($prefix . $alias . '.', $alias . '.', $select);
            }
        }

        return $select;
    }     

    /**
     *
     * SELECT sorgusunun sql raw halini çalıştırır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param Class $builder
     * @param int $type
     * @return array|object 
     *
     */
    public static function getResult($builder, $type)
    {

        $sqlQuery = self::getSqlSelect($builder->sqlData, $builder->prefix);
        return $builder->execute($sqlQuery['query'], $sqlQuery["value"], $type);
    }

    /**
     *
     * SELECT sorgusunun sql raw halini oluşturur ve döner.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $sqlData
     * @param string $prefix
     * @return string 
     *
     */
    public static function getSqlSelect($sqlData, $prefix)
    {

        $whereCombine = self::whereCombine($sqlData, $prefix);
        return array('query' => self::selectCombine($sqlData, $prefix) . ' ' . $whereCombine['query'], 'value' => $whereCombine['value']);
    }

    /**
     *
     * Update işlemini yapar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param Class $builder
     * @param string $table
     * @return int 
     *
     */
	public static function update($builder, $table)
	{

		if (count($builder->sqlData['set']) <= 0 || empty($table)) {
			return 0;
		}

		$whereCombine = self::whereCombine($builder->sqlData, $builder->prefix);

		$setList = array();
        foreach($builder->sqlData['set'] as $up){
            $setList[] = $up["field1"] . ' = ' . $up["field2"];
        }

        $sqlQuery = sprintf('UPDATE %s%s SET %s %s', $builder->sqlData['prefix'], $table, implode(',', $setList), $whereCombine['query']);
        return $builder->execute($sqlQuery, $whereCombine["value"], 5);       

	}

}
