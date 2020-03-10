<?php 

namespace IS\QueryBuilder\Database\Builder;

use IS\QueryBuilder\Database\Utility\Utility;

Class Where
{

    /**
     *
     * Sorguya where kısmı için groupby ekler
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $groupByData
     * @return array 
     *
     */
    public static function groupBy($builder, $groupByData)
    {

        foreach (Utility::stringToArray($groupByData) as $groupby) {
            $builder['groupby'][] = $groupby;
        }

        return $builder;
    }

    /**
     *
     * Sorguya where kısmı için limit ekler
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param int $min
     * @param int $max
     * @return array 
     *
     */
    public static function limit($builder, $min, $max = 0)
    {

        if ($max > 0) {
            $builder["limit"]["text"]["limit"] = "LIMIT ?, ?";
            $builder["limit"]["value"] = array("val1" => $min, "val2" => $max);
        }else{
            $builder["limit"]["text"]["limit"] = "LIMIT ?";
            $builder["limit"]["value"]["val1"] = $min;
        }

        return $builder;
    }

    /**
     *
     * Sorguya where için gruplama imkanı sağlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $seperator
     * @param string $andOr
     * @return array 
     *
     */
    public static function groupQuery($builder, $seperator = "(" , $andOr = "AND")
    {

        $builder["where"][] = $andOr;
        $builder["where"][] = $seperator;
        return $builder;

    }

    /**
     *
     * Sorguya where için where IN kullanım imkanı sağlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $column
     * @param array $data
     * @param string $andOr
     * @param string $not
     * @return array 
     *
     */
    public static function whereIn($builder, $column, $data, $andOr, $not = "")
    {
        
        if (count($data) <= 0) {
            return $builder;
        }

        $builder["where"][] = $andOr;

        $questions = array();
        foreach ($data as $dataValue) {
            $questions[] = "?";
            $builder["value"][] = $dataValue;
        }

        $builder["where"][] = sprintf("%s %s IN(%s)", trim($column), $not, implode(",", $questions));
        return $builder;
    }

    /**
     *
     * Sorguya OR BETWEEN NOT eklemesi yapar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $column
     * @param mixed $firstValue
     * @param mixed $lastValue
     * @param string $andOr
     * @param string $not
     * @return array 
     *
     */
    public static function between($builder, $column, $firstValue, $lastValue, $andOr, $not = "")
    {
        
        if (empty($column) || empty($firstValue) || empty($lastValue)) {
            return $builder;
        }

        $builder['where'][] = $andOr;
        $builder['where'][] = sprintf("%s %s BETWEEN ? AND ?", $column, $not);
        $builder['value'][] = $firstValue;
        $builder['value'][] = $lastValue;
        return $builder;
    }

    /**
     *
     * Sorguya like eklemesi yapar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $column
     * @param mixed $value
     * @param string $andOr
     * @param string $not
     * @param string $type
     * @return array 
     *
     */
    public static function like($builder, $column, $value, $andOr, $not, $type = "")
    {

        $value = Utility::likeEscape($value);
        if(empty($column)){
            return $builder;
        }

        switch (strtolower($type))
        {
            case 'left' :  $value = '%' . $value;  break;
            case 'right':  $value =  $value . '%';  break;
            default     :  $value = '%' . $value . '%'; break;
        }

        $builder["where"][] = $andOr;
        $builder["where"][] = sprintf("%s %s LIKE ?", $column, $not);
        $builder["value"][] = $value;
        return $builder;
    }

    /**
     *
     * Belirtilen kolonları birleştirir ve like ile arar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $column
     * @param mixed $value
     * @param string $type
     * @param string $andOr
     * @return array 
     *
     */
    public static function concat($builder, $column, $value, $type, $andOr)
    {

        if (!is_array($column)) {
            return $builder;
        }

        $value = Utility::likeEscape($value);
        switch (strtolower($type))
        {
            case 'left' :  $value = '%' . $value;  break;
            case 'right':  $value =  $value . '%';  break;
            default     :  $value = '%' . $value . '%'; break;

        }

        $builder["where"][] = $andOr;
        $builder["where"][] = sprintf("CONCAT(%s) LIKE ?", implode(', \' \' ,',$column));
        $builder["value"][] = $value;
        return $builder;
    }

    /**
     *
     * Sorguya having uygular.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string $havingKey
     * @param mixed $value
     * @return array 
     *
     */
    public static function having($builder, $havingKey, $value)
    {

        $seperator = " = ";
        if(strpbrk($havingKey, implode("", Utility::$syntaxValues))){
            $seperator = "";
        }

        $builder["having"]["text"][0] = sprintf("%s%s ?", $havingKey, $seperator);
        $builder["having"]["value"][0] = $value;
        return $builder;
    }

    /**
     *
     * Sorguya Where kısmını uygular
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string|array $columns
     * @param string $seperator
     * @param string $value
     * @param string $andOr
     * @return array
     *
     */
    public static function where($builder, $columns, $seperator, $value, $andOr)
    {

        if (!is_array($columns)) 
        {
            if ($value === null) {
                $columns = array(array($columns, $seperator));
            }else{
                $columns = array(array($columns, $seperator, $value));
            }
        }


        foreach ($columns as $columnKey => $columnData) 
        {

            if (!is_array($columnData)) {
                $columnData = array($columnKey, $columnData);
            }

            $dataCount   = count($columnData);
            $column      = trim($columnData[0]);

            $seperator   = $dataCount == 3 && $columnData[2] !== null ? trim($columnData[1]) : '=';
            $columnValue = $dataCount == 3 && $columnData[2] !== null ? trim($columnData[2]) : trim($columnData[1]);

            if(!in_array($seperator, Utility::$syntaxValues)){
                $seperator = "=";
            }

            $builder['where'][] = $andOr;
            $builder['where'][] = sprintf("%s %s ?", $column, $seperator); 
            $builder['value'][] = $columnValue;
        }

        return $builder;
    }

}
