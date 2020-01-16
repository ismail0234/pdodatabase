<?php 

namespace IS\QueryBuilder\Database\Builder;

Class Update
{

    /**
     *
     * Update sorgusu için verileri set olarak uygular.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array $builder
     * @param string|array $columns
     * @param string $seperator
     * @param string $value
     * @return array 
     *
     */
    public static function set($builder, $columns, $seperator = '', $value = null)
    {

        if (!is_array($columns)) 
        {
            if ($value == null) {
                $columns = array(array($columns, $seperator));
            }else{
                $columns = array(array($columns, $seperator, $value));
            }
        }

        foreach ($columns as $columnData) 
        {
            $dataCount   = count($columnData);
            $column      = trim($columnData[0]);

            $seperator   = $dataCount == 3 && $columnData[2] !== null ? trim($columnData[1]) : '?';
            $columnValue = $dataCount == 3 && $columnData[2] !== null ? trim($columnData[2]) : trim($columnData[1]);

            $builder['set'][] = array('field1' => $column, 'field2' => $seperator);
            $builder['value'][] = $columnValue;
        }

        return $builder;
    }

}