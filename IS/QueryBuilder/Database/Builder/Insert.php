<?php 

namespace IS\QueryBuilder\Database\Builder;

Class Insert
{

    /**
     *
     * Veri ekleme için kullanılır
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $prefix
     * @param string $table
     * @param array $field
     * @param array $data
     * @return array 
     *
     */
	public static function addBulkData($prefix, $table, $field, $data)
	{
		return self::addData($prefix, $table, $field, $data);
	}

    /**
     *
     * Toplu Veri ekleme için kullanılır
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $prefix
     * @param string $table
     * @param array $field
     * @param array $data
     * @return array 
     *
     */
	public static function addData($prefix, $table, $field, $data)
	{

		$insert = array('columns'   => array(), 'questions' => array(), 'values'    => array());
		foreach ($data as $column => $dataValue) 
		{

			if (!is_array($dataValue)) 
			{
				$insert['columns'][] = $column;
				$insert['questions'][] = '?';
				$insert['values'][]    = $dataValue;
			}
			else
			{
				$questions = array();
				foreach ($dataValue as $value) {
					$questions[] = '?';
					$insert['values'][] = $value;
				}

				$insert['questions'][] = '(' . implode(',', $questions) . ')';
			}

		}

		if (count($field) > 0) {
			$insert['columns'] = $field;
			$insert['questions'] = array(implode(',', $insert['questions']));
		}else{
			$insert['questions'] = array('(' . implode(',', $insert['questions']) . ')');
		}
		
        $query = 'INSERT INTO ' . $prefix . $table . ' (' . implode(',', $insert['columns']) . ') VALUES ' . implode(',', $insert['questions']);
        return array('sqlQuery' => $query, 'values' => $insert['values']);
	}

}