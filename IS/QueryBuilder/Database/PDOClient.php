<?php 
/**
 * Class PDOClient
 *
 * @author Ismail Satilmis
 * @mail ismaiil_0234@hotmail.com
 *
 */
namespace IS\QueryBuilder\Database;

use IS\QueryBuilder\Database\Builder\Select;
use IS\QueryBuilder\Database\Builder\Insert;
use IS\QueryBuilder\Database\Builder\Where;
use IS\QueryBuilder\Database\Builder\Update;
use IS\QueryBuilder\Database\Builder\Special;
use IS\QueryBuilder\Database\Builder\Response;

use IS\QueryBuilder\Database\Helper\Database;
use IS\QueryBuilder\Database\Helper\Debug;

Class PDOClient extends Database
{

	/**
	 *
	 * Veritabanı bağlantısı için alt sınıfa gerekli bilgileri gönderir.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $configuration
	 *
	 */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    /**
     *
     * İstenilen kolon adlarını sorguya ekler.
     * 
     * Example: $db->select('title')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param array|string $select
     * @return this 
     *
     */
    public function select($select = array())
    {

        $this->migrateSQL(Select::select($this->sqlData, $select));
        return $this;

    }

    /**
     *
     * İstenilen tabloyu sorguya ekler.
     * 
     * Example: $db->from('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $from
     * @return this 
     *
     */
    public function from($from)
    {   

        $this->migrateSQL(Select::from($this->sqlData, $from));
        return $this;
    }

    /**
     *
     * min işlemini belirlenen kolona uygular
     *
     * Example: $db->min('schoolNumber')
     * Example: $db->min('schoolNumber', 'newSchoolNumber')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return this 
     *
     */
    public function min($select, $alias = "")
    {

		$this->migrateSQL(Select::selectMultiple($this->sqlData, $select, $alias, 'MIN'));
		return $this;

    }

    /**
     *
     * max işlemini belirlenen kolona uygular
     *
     * Example: $db->max('schoolNumber')
     * Example: $db->max('schoolNumber', 'newSchoolNumber')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return this 
     *
     */
    public function max($select, $alias = "")
    {

       	$this->migrateSQL(Select::selectMultiple($this->sqlData, $select, $alias, 'MAX'));
       	return $this;

    }

    /**
     *
     * avg işlemini belirlenen kolona uygular
     *
     * Example: $db->avg('schoolNumber')
     * Example: $db->avg('schoolNumber', 'newSchoolNumber')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return this 
     *
     */
    public function avg($select, $alias = "")
    {

       	$this->migrateSQL(Select::selectMultiple($this->sqlData, $select, $alias, 'AVG'));
       	return $this;

    }    

    /**
     *
     * sum işlemini belirlenen kolona uygular
     *
     * Example: $db->sum('schoolNumber')
     * Example: $db->sum('schoolNumber', 'newSchoolNumber')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return this 
     *
     */
    public function sum($select, $alias = "")
    {

       	$this->migrateSQL(Select::selectMultiple($this->sqlData, $select, $alias, 'SUM'));
       	return $this;

    }

    /**
     *
     * count işlemini belirlenen kolona uygular
     *
     * Example: $db->count('schoolNumber')
     * Example: $db->count('schoolNumber', 'newSchoolNumber')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $select
     * @param string $alias
     * @return this 
     *
     */
    public function count($select, $alias = "")
    {

       	$this->migrateSQL(Select::selectMultiple($this->sqlData, $select, $alias, 'COUNT'));
       	return $this;

    }    

    /**
     *
     * istenilen orderby değerini sorguya ekler.
     *
     * Example: $db->orderby('schoolNumber')
     * Example: $db->orderby('schoolNumber', 'DESC')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $columns
     * @param string $sorting
     * @return this 
     *
     */
    public function orderby($columns, $sorting = "")
    {

        $this->migrateSQL(Select::orderBy($this->sqlData, $columns , $sorting));
        return $this;

    }

    /**
     *
     * istenilen orderby değerini sorguya ekler.
     *
     * Example: $db->join('orders', 'orders.uid = users.id')
     * Example: $db->join('orders', 'orders.uid = users.id', 'LEFT')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @param string $query
     * @param string $join
     * @return this 
     *
     */
    public function join($table, $query, $join = "")
    {

        $this->migrateSQL(Select::join($this->sqlData, $table, $query, $join));
        return $this;

    }

    /**
     *
     * Veri ekleme için kullanılır
     *
     * Example: $db->insert('orders', array('column1' => 'data1', 'column2' => 'data2'))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @param array $data
     * @return int 
     *
     */
    public function insert($table, $data)
    {
        $response = Insert::addData($this->prefix, $table, array(), $data);
        return $this->execute($response['sqlQuery'], $response['values'], 6);
    }

    /**
     *
     * Toplu Veri ekleme için kullanılır
     *
     * Example: $db->bulkInsert('orders', array('column1', 'column2'), array(array('data1', 'data2'), array('data3', 'data4')))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @param array $field
     * @param array $data
     * @return int 
     *
     */
    public function bulkInsert($table, $field, $data)
    {
        $response = Insert::addBulkData($this->prefix, $table, $field, $data);
        return $this->execute($response['sqlQuery'], $response['values'], 5);
    }

    /**
     *
     * Sorguya where kısmı için groupby ekler.
     *
     * Example: $db->groupby('groupOne')
     * Example: $db->groupby('groupOne, groupTwo')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $groupByData
     * @return this 
     *
     */
    public function groupby($groupByData)
    {

        $this->migrateSQL(Where::groupBy($this->sqlData, $groupByData));
        return $this;
    }

    /**
     *
     * Sorguya where kısmı için limit ekler
     *
     * Example: $db->limit(20)
     * Example: $db->limit(0, 20)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param int $min
     * @param int $max
     * @return this 
     *
     */
    public function limit($min, $max = 0)
    {

        $this->migrateSQL(Where::limit($this->sqlData, $min, $max));
        return $this;
    }

    /**
     *
     * Sorguya where için gruplama başlatır.
     *
     * Example: $db->groupStart()
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return this 
     *
     */
    public function groupStart()
    {

        $this->migrateSQL(Where::groupQuery($this->sqlData));
        return $this;
    }
 
     /**
     *
     * Sorguya where için OR ile gruplama başlatır.
     *
     * Example: $db->orGroupStart()
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return this 
     *
     */
    public function orGroupStart()
    {

        $this->migrateSQL(Where::groupQuery($this->sqlData, "(", "OR"));
        return $this;
    }

    /**
     *
     * Sorguya where için gruplamayı bitirir.
     *
     * Example: $db->groupEnd()
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return this 
     *
     */
    public function groupEnd()
    {

        $this->migrateSQL(Where::groupQuery($this->sqlData, ")"));
        return $this;
    }

    /**
     *
     * Sorguya where için where IN kullanım imkanı sağlar.
     *
     * Example: $db->whereIn('id', array(1, 2, 3))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param array $data
     * @return this
     *
     */
    public function whereIn($column, $data = array())
    {

        $this->migrateSQL(Where::whereIn($this->sqlData, $column, $data, "AND"));
        return $this;
    }

    /**
     *
     * Sorguya where için where IN kullanım imkanı sağlar.
     *
     * Example: $db->whereNotIn('id', array(1, 2, 3))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param array $data
     * @return this
     *
     */
    public function whereNotIn($column, $data = array())
    {

        $this->migrateSQL(Where::whereIn($this->sqlData, $column, $data, "AND" , "NOT"));
        return $this;
    }

    /**
     *
     * Sorguya where için where IN kullanım imkanı sağlar.
     *
     * Example: $db->orWhereIn('id', array(1, 2, 3))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param array $data
     * @return this
     *
     */
    public function orWhereIn($column, $data = array())
    {

        $this->migrateSQL(Where::whereIn($this->sqlData, $column, $data, "OR"));
        return $this;
    }

    /**
     *
     * Sorguya where için where IN kullanım imkanı sağlar.
     *
     * Example: $db->orWhereNotIn('id', array(1, 2, 3))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param array $data
     * @return this
     *
     */
    public function orWhereNotIn($column, $data = array())
    {

        $this->migrateSQL(Where::whereIn($this->sqlData, $column, $data, "OR" , "NOT"));
        return $this;
    }

    /**
     *
     * Sorguya BETWEEN eklemesi yapar.
     *
     * Example: $db->between('id', 100, 200)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function between($column, $first, $last)
    {

        $this->migrateSQL(Where::between($this->sqlData, $column, $first, $last, "AND"));
        return $this;
    }

    /**
     *
     * Sorguya OR BETWEEN eklemesi yapar.
     *
     * Example: $db->orBetween('id', 100, 200)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function orBetween($column, $first, $last)
    {

        $this->migrateSQL(Where::between($this->sqlData, $column, $first, $last, "OR"));
        return $this;
    }

    /**
     *
     * Sorguya BETWEEN NOT eklemesi yapar.
     *
     * Example: $db->betweenNot('id', 100, 200)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function betweenNot($column, $first, $last)
    {

        $this->migrateSQL(Where::between($this->sqlData, $column, $first, $last, "AND", "NOT"));
        return $this;
    }

    /**
     *
     * Sorguya OR BETWEEN NOT eklemesi yapar.
     *
     * Example: $db->orBetweenNot('id', 100, 200)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function orBetweenNot($column, $first, $last)
    {

        $this->migrateSQL(Where::between($this->sqlData, $column, $first, $last, "OR", "NOT"));
        return $this;
    }

    /**
     *
     * Sorguya LIKE eklemesi yapar.
     *
     * Example: $db->like('name', 'search')
     * Example: $db->like('name', 'search', 'right')
     * Example: $db->like('name', 'search', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function like($column, $val, $type = 'center')
    {

        $this->migrateSQL(Where::like($this->sqlData, $column, $val, 'AND', '', $type));
        return $this;
    }

    /**
     *
     * Sorguya OR LIKE eklemesi yapar.
     *
     * Example: $db->orLike('name', 'search')
     * Example: $db->orLike('name', 'search', 'right')
     * Example: $db->orLike('name', 'search', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function orLike($column, $val, $type = 'center')
    {

        $this->migrateSQL(Where::like($this->sqlData, $column, $val, 'OR', '', $type));
        return $this;       
    }

    /**
     *
     * Sorguya NOT LIKE eklemesi yapar.
     *
     * Example: $db->likeNot('name', 'search')
     * Example: $db->likeNot('name', 'search', 'right')
     * Example: $db->likeNot('name', 'search', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function likeNot($column, $val, $type = 'center')
    {

        $this->migrateSQL(Where::like($this->sqlData, $column, $val, 'AND', 'NOT', $type));
        return $this;       
    }

    /**
     *
     * Sorguya OR NOT LIKE eklemesi yapar.
     *
     * Example: $db->orLikeNot('name', 'search')
     * Example: $db->orLikeNot('name', 'search', 'right')
     * Example: $db->orLikeNot('name', 'search', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $column
     * @param mixed $first
     * @param mixed $last
     * @return this 
     *
     */
    public function orLikeNot($column, $val, $type = 'center')
    {

        $this->migrateSQL(Where::like($this->sqlData, $column, $val, 'OR', 'NOT', $type));
        return $this;       
    } 

    /**
     *
     * Belirtilen kolonları birleştirir ve like ile arar.
     *
     * Example: $db->concatLike(array('name', 'surname'), 'ismail')
     * Example: $db->concatLike(array('name', 'surname'), 'ismail', 'right')
     * Example: $db->concatLike(array('name', 'surname'), 'ismail', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $field
     * @param mixed $val
     * @param string $type
     * @return this
     *
     */
    public function concatLike($field, $val, $type = 'center')
    {

        $this->migrateSQL(Where::concat($this->sqlData, $field, $val, $type, 'AND'));
        return $this;
    }

    /**
     *
     * Belirtilen kolonları birleştirir ve like ile arar.
     *
     * Example: $db->orConcatLike(array('name', 'surname'), 'ismail')
     * Example: $db->orConcatLike(array('name', 'surname'), 'ismail', 'right')
     * Example: $db->orConcatLike(array('name', 'surname'), 'ismail', 'left')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $field
     * @param mixed $val
     * @param string $type
     * @return this
     *
     */
    public function orConcatLike($field, $val, $type = 'center')
    {
        
        $this->migrateSQL(Where::concat($this->sqlData, $field, $val, $type, 'OR'));
        return $this;
    }

    /**
     *
     * Sorguya having uygular.
     *
     * Example: $db->having('id', 1)
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $havingKey
     * @param mixed $value
     * @return this 
     *
     */
    public function having($havingKey, $value)
    {

        $this->migrateSQL(Where::having($this->sqlData, $havingKey, $value));
        return $this;
    }

    /**
     *
     * Sorguya Where kısmını uygular
     *
     * Example: $db->where('id', 1)
     * Example: $db->where('id', '!=' 1)
     * Example: $db->where(array('id' => 1))
     * Example: $db->where(array('id' => 1, 'type' => 'post'))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string|array $columns
     * @param string $seperator
     * @param string $value
     * @return this
     *
     */
    public function where($column, $seperator = "", $value = null)
    {

        $this->migrateSQL(Where::where($this->sqlData, $column, $seperator, $value , 'AND'));
        return $this;
    }

    /**
     *
     * Sorguya Where kısmını uygular
     *
     * Example: $db->orWhere('id', 1)
     * Example: $db->orWhere('id', '!=' 1)
     * Example: $db->orWhere(array('id' => 1))
     * Example: $db->orWhere(array('id' => 1, 'type' => 'post'))
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string|array $columns
     * @param string $seperator
     * @param string $value
     * @return this
     *
     */
    public function orWhere($column, $seperator = "", $value = null)
    {

        $this->migrateSQL(Where::where($this->sqlData, $column, $seperator, $value , 'OR'));
        return $this;
    }

    /**
     *
     * CHECKSUM Sorgusunu çalıştırır.
     *
     * Example: $db->checksumTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function checksumTable($table)
    {

        return Special::tableCommand($this, 'CHECKSUM TABLE', $table);
    }

    /**
     *
     * OPTIMIZE Sorgusunu çalıştırır.
     *
     * Example: $db->optimizeTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function optimizeTable($table)
    {

        return Special::tableCommand($this, 'OPTIMIZE TABLE', $table);
    }
    
    /**
     *
     * REPAIR Sorgusunu çalıştırır.
     *
     * Example: $db->repairTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function repairTable($table)
    {

        return Special::tableCommand($this, 'REPAIR TABLE', $table);
    }
    
    /**
     *
     * CHECK Sorgusunu çalıştırır.
     *
     * Example: $db->checkTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function checkTable($table)
    {

        return Special::tableCommand($this, 'CHECK TABLE', $table);
    }    
    
    /**
     *
     * OPTIMIZE DROP çalıştırır.
     *
     * Example: $db->dropTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function dropTable($table)
    {
        
        return Special::tableCommand($this, 'DROP TABLE', $table);
    }

    /**
     *
     * ANALYZE Sorgusunu çalıştırır.
     *
     * Example: $db->analyzeTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function analyzeTable($table)
    {

        return Special::tableCommand($this, 'ANALYZE TABLE', $table);
    }

    /**
     *
     * DELETE Sorgusunu çalıştırır.
     *
     * Example: $db->emptyTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function emptyTable($table)
    {

        return Special::tableCommand($this, 'DELETE FROM', $table, false);
    }
    
    /**
     *
     * TRUNCATE Sorgusunu çalıştırır.
     *
     * Example: $db->truncateTable('users')
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return array 
     *
     */
    public function truncateTable($table)
    {

        return Special::tableCommand($this, 'TRUNCATE TABLE', $table, false, 'none');
    }

    /**
     *
     * Update sorgusu için verileri set olarak uygular.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string|array $columns
     * @param string $seperator
     * @param string $value
     * @return this 
     *
     */
    public function set($column, $seperator = "", $value = null)
    {

        $this->migrateSQL(Update::set($this->sqlData, $column, $seperator, $value));
        return $this;
    }

    /**
     *
     * Silme işlemi yapar.
     *
     * Example: $db->delete('users');
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return int 
     *
     */
    public function delete($table)
    {

        return Response::delete($this, $table);
    }

    /**
     *
     * SELECT sorgusunun sql raw halini oluşturur ve döner.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return string 
     *
     */
    public function getSqlSelect()
    {

        return Response::getSqlSelect($this->sqlData, $this->prefix);
    }

    /**
     *
     * Sorguyu çalıştırır ve sonucu döner.
     *
     * Example: $db->resultObject();
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return object 
     *
     */
    public function resultObject()
    {  

        return Response::getResult($this, 1);
    }

    /**
     *
     * Sorguyu çalıştırır ve sonucu döner.
     *
     * Example: $db->resultArray();
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return array 
     *
     */
    public function resultArray()
    {

        return Response::getResult($this, 2);
    }

    /**
     *
     * Sorguyu çalıştırır ve sonucu döner.
     *
     * Example: $db->getObject();
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return object 
     *
     */
    public function getObject()
    { 

        return Response::getResult($this, 3);
    }

    /**
     *
     * Sorguyu çalıştırır ve sonucu döner.
     *
     * Example: $db->getArray();
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return array 
     *
     */
    public function getArray()
    { 

        return Response::getResult($this, 4);
    }

    /**
     *
     * Update işlemini yapar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $table
     * @return int 
     *
     */
    public function update($table)
    {

        return Response::update($this, $table);
    }

    /**
     *
     * Exception durumunu ayarlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param boolean $type
     *
     */
    public function setException($type)
    {

        $this->_setException($type);
    }

    /**
     *
     * Transaction işlemini başlatır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function beginTransaction()
    {

        $this->_beginTransaction();    
    }

    /**
     *
     * Sorgular eğer bir exception verirse rollback ile geri alınır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function rollBack()
    {

        $this->_rollBack();    
    }

    /**
     *
     * Sorgular üzerinde bir hata yok ise sorguların tamamı çalıştırılır.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     *
     */
    public function commit()
    {
        
        $this->_commit();   
    }

    /**
     *
     * Ekrana sql sorgularının detaylarını basar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @return string 
     *
     */
    public function debugOutput()
    {

    	return Debug::debugOutput($this->debug);
    }

    /**
     *
     * Özel sql sorgusu çalıştırmayı sağlar.
     *
     * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
     * @param string $queryString
     * @param array $values
     * @param int $type
     * @return mixed 
     *
     */
    public function query($queryString, $values, $type)
    {

        return $this->execute($queryString, $values, $type);
    }

}
