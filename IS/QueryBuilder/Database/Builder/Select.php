<?php 

namespace IS\QueryBuilder\Database\Builder;

use IS\QueryBuilder\Database\Utility\Utility;

Class Select
{

    /**
     *
     * İstenilen kolon adlarını sorguya ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param array|string $selectData
     * @return array 
     *
     */
    public static function select($builder, $selectData)
    {

        foreach (Utility::stringToArray($selectData) as $select) {
            $builder['select'][] = $select;
        }
        
        return $builder;
    }

    /**
     *
     * kolonlar üzerinde işlemler yaparak (min, max vs...) sorguya ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $select
     * @param string $alias
     * @param string $properties
     * @return array 
     *
     */
    public static function selectMultiple($builder, $select, $alias , $properties)
    {

        foreach (Utility::selectDefaultValue($select, $alias) as $key => $value) {
            $builder["select"][] = $properties . '(' . $key .') AS '. $value;
        }

        return $builder;
    }

    /**
     *
     * İstenilen tabloyu sorguya ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $fromData
     * @return array 
     *
     */
    public static function from($builder, $fromData)
    {

        $builder['table'][0] = $builder['prefix'] . $fromData;
        return $builder;
    }

    /**
     *
     * istenilen orderby değerini sorguya ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $columns
     * @param string $sorting
     * @return array 
     *
     */
    public static function orderby($builder, $columns, $sorting = "")
    {


        $orderbyData = Utility::selectDefaultValue($columns, $sorting);
        if (count($orderbyData) <= 0) {
            return $builder;
        }

        $orderbyColumn = key($orderbyData);
        $orderbyValue = current($orderbyData);

        if (!in_array($orderbyValue, Utility::$orderbyValues)) {
            $orderbyValue = 'ASC';
        }

        $builder['orderby'][] = $orderbyColumn . ' '. $orderbyValue;
        return $builder;
    }

    /**
     *
     * istenilen inner join sorgusunu ana sorguya ekler.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $table
     * @param string $query
     * @param string $joinType
     * @return array 
     *
     */
    public static function join($builder, $table, $query, $joinType = 'INNER')
    {

        if (!in_array($joinType, Utility::$joinValues)) {
            $joinType = 'INNER';
        }

        if (strstr($table, 'as') !== false) 
        {
            $alias = Utility::getAlias($table);
            if ($alias !== false) {
                $builder['ignoreAlias'][$alias] = $alias;
            }
        }        

        $builder["join"][] = ' ' . $joinType . ' JOIN ' . $builder['prefix'] . $table . ' ON ' . $query;
        return $builder;
    }

}