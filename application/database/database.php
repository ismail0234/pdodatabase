<?php 

namespace PdoDatabase;

Class PDO_MYSQL
{

     protected $debugcss = '<style type="text/css">body{margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}h1{border-bottom:1px solid #D0D0D0;font-size:19px;font-weight:400;margin:0 0 14px;padding:14px 15px 10px}#container{margin:10px;border:1px solid #D0D0D0;box-shadow:0 0 8px #D0D0D0}p{margin:12px 15px}</style>';

     protected $prefix = '';
     /*
      * Query Log 
      * 0 => CLOSE
      * 1 => Open 
      */
     protected $querylog = 1;

     public $debug = [];

     public $sql = [
        
        "select"   => [],
        "from"     => [],
        "where"    => [],
        "value"    => [],
        "set"      => [],
        "orderby"  => [],
        "groupby"  => [],
        "distinct" => [],
        "join"     => [],
        "having"   => [
            "text"  => [] , 
            "value" => []
        ],
        "special"   => [ 
            "text"  => [] , 
            "value" => []
        ],
        "limit" => ["text" => []],

     ];

     private $sqlsyntax = ["=", "!=", "<", "<>" , ">", "<=", ">="];

     private $and_or = ["AND", "OR"];

     private $pdo = null;

     public function __construct($array = [])
     {

        $array = $this->install($array);

        $connstring = $array["dbengine"].":host=".$array["ip"].";dbname=".$array["database"].";charset=".$array["charset"];

		try{


			$this->pdo = new PDO($connstring,$array["username"],$array["password"]);

			$this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$this->prefix = $array["prefix"];

		}catch(Exception $e){

            $this->debug('Database Connection Failed',array(0 => "1049",2 => $e->getMessage()),$connstring);

		}

	}

    private function install($array)
    {

        $array["ip"]       = isset($array["ip"])       && !empty(trim($array["ip"]))       ? $array["ip"]       : 'localhost';
        $array["dbengine"] = isset($array["dbengine"]) && !empty(trim($array["dbengine"])) ? $array["dbengine"] : 'mysql';
        $array["charset"]  = isset($array["charset"])  && !empty(trim($array["charset"]))  ? $array["charset"]  : 'utf8';
        $array["database"] = isset($array["database"]) && !empty(trim($array["database"])) ? $array["database"] : '';
        $array["username"] = isset($array["username"]) && !empty(trim($array["username"])) ? $array["username"] : '';
        $array["password"] = isset($array["password"]) && !empty(trim($array["password"])) ? $array["password"] : '';
        $array["prefix"]   = isset($array["prefix"])   && !empty(trim($array["prefix"]))   ? $array["prefix"]   : '';
        $this->querylog    = isset($array["querylog"]) && !empty(trim($array["querylog"])) ? $array["querylog"] : 1;

        return $array;

    }

    private function debug($name,$arr = [],$sql)
    {

       echo $this->debugcss;

       echo '<div id="container"><h1>'.$name.'</h1>';
       echo '<p> Error Number: '.(isset($arr[0]) ? $arr[0]:'-');
       echo '<p> '.(isset($arr[2]) ? $arr[2]:'-');
       echo '<p> '.(isset($sql) ? $sql:'-');
       echo '</div>';

       exit;
    }

    private function debugAdd($sql,$array,$speed)
    {

        $this->debug[] = [
            "sql"     => $sql,
            "value"   => $array,
            "speed" => $speed,
        ];

    }


    private function pdoexec($sql,$array = [],$status = 1)
    {

        if($this->querylog !== 0)
        {

            $start = microtime(1);

        }

        $pre = $this->pdo->prepare($sql);

        $errorCode = $this->pdo->errorInfo();

        if($errorCode[0] > 0)
        {

            $this->debug('SQL Prepare Error',$errorCode,$sql);

        }
        else if(!$pre->execute($array))
        {

            $this->debug('SQL Execute Error',$pre->errorInfo(),$sql);

        }

        $sonuc = true;

        switch($status)
        {

            case 1: $sonuc = $pre->fetchAll(PDO::FETCH_OBJ);  break;
            case 2: $sonuc = $pre->fetchAll();  break;
            case 3: $sonuc = $pre->fetch(PDO::FETCH_OBJ);  break;
            case 4: $sonuc = $pre->fetch();  break;
            case 5: $sonuc = $pre->rowcount();  break;
            case 6: $sonuc = $this->pdo->lastInsertId();  break;

        }


        if($this->querylog !== 0)
        {

            $finish = microtime(1);

            $this->debugAdd($sql,$array,($finish - $start));

        }

        $this->clearQuery();

        return $sonuc;

    }

    public function select($select = [])
    {

        if(is_string($select))
        {

            $select = explode(',',$select);

        }
      
        foreach($select as $slc)
        {

           $this->sql["select"][] = $slc;

        }

        return $this;

    }

    public function group_start()
    {

        return $this->group_function();

    }
 
    public function or_group_start()
    {

        return $this->group_function('(','OR');

    }

    public function group_end()
    {

        return $this->group_function(')');

    }

    public function min($select = [],$sec= '')
    {

       return $this->select_multiple($select,$sec,$mm = 'MIN');

    }

    public function max($select = [],$sec= '')
    {

       return $this->select_multiple($select,$sec,$mm = 'MAX');

    }

    public function avg($select = [],$sec= '')
    {

       return $this->select_multiple($select,$sec,$mm = 'AVG');

    }    

    public function sum($select = [],$sec= '')
    {

       return $this->select_multiple($select,$sec,$mm = 'SUM');

    }

    public function count($select = [],$sec= '')
    {

       return $this->select_multiple($select,$sec,$mm = 'COUNT');

    }        

    public function result()
    {  

        return $this->get_result(1);

    }

    public function result_array()
    {

        return $this->get_result(2);

    }

    public function get()
    { 

        return $this->get_result(3);

    }

    public function get_array()
    { 

        return $this->get_result(4);

    }     

    public function query($select , $array , $type)
    {

        return $this->pdoexec($select , $array , $type);

    }

    public function drop($table)
    {

        return $this->pdoexec('DROP TABLE '.$this->prefix . trim($table));

    }

    public function empty_table($table)
    {

        return $this->pdoexec('DELETE FROM '.$this->prefix . trim($table));

    }

    public function truncate($table)
    {

        return $this->pdoexec('TRUNCATE '.$this->prefix . trim($table));

    }

    public function analyze($table)
    {

        return $this->pdoexec('ANALYZE TABLE '.$this->prefix . trim($table),[],4);

    }

    public function where($field,$two = '',$three = '')
    {

       return $this->where_function($field,$two,$three,'AND');

    }

    public function or_where($field,$two = '',$three = '')
    {

       return $this->where_function($field,$two,$three,'OR');

    }

    public function where_in($field,$arr = [])
    {

       return $this->where_in_function($field,$arr,'AND');

    }

    public function where_not_in($field,$arr = [])
    {

       return $this->where_in_function($field,$arr,'AND','NOT');

    }

    public function or_where_in($field,$arr = [])
    {

       return $this->where_in_function($field,$arr,'OR');

    }

    public function or_where_not_in($field,$arr = [])
    {

       return $this->where_in_function($field,$arr,'OR','NOT');

    }

    public function between($field,$one,$two)
    {

       return $this->where_between_function($field,$one,$two,'AND');

    }

    public function or_between($field,$one,$two)
    {

       return $this->where_between_function($field,$one,$two,'OR');

    }

    public function between_not($field,$one,$two)
    {

       return $this->where_between_function($field,$one,$two,'AND','NOT');

    }

    public function or_between_not($field,$one,$two)
    {

       return $this->where_between_function($field,$one,$two,'OR','NOT');

    }

    public function like($field,$val,$type = 'center')
    {

        return $this->where_like_function($field,$val,'AND','',$type);

    }

    public function or_like($field,$val,$type = 'center')
    {

        return $this->where_like_function($field,$val,'OR','',$type);

    }

    public function like_not($field,$val,$type = 'center')
    {

        return $this->where_like_function($field,$val,'AND','NOT',$type);

    }

    public function or_like_not($field,$val,$type = 'center')
    {

        return $this->where_like_function($field,$val,'OR','NOT',$type);

    }    

    public function having($array = [],$sec = '')
    {

        return $this->having_function($array,$sec);

    }
   /* 
    public function distinct($select = '')
    {

        if(is_string($select))
        {

            $select = explode(',',$select);

        }
      
        if(is_array($select) && count($select) > 0)
        {

            foreach($select as $slc)
            {

               $this->sql["distinct"][] = $slc;

            }

        }

        return $this;

    }
    */

    public function join($table,$query,$join = '')
    {

        $this->sql["join"][] = ' '. trim($join) . ' JOIN ' . $this->prefix . $table.' ON '.$query;

        return $this;
    }

    private function addPrefix($name)
    {

        $pattern = '@(\w+)\.(\w+)@si'; 

        return preg_replace($pattern, $this->prefix . '$0',$name);
  
    }

    public function groupby($array = [])
    {

        if(is_string($array))
        {

            $array = explode(',',$array);

        }
      
        foreach($array as $frm)
        {

           $this->sql['groupby'][] = $frm;

        }

        return $this;

    }

    public function having_function($array,$sec)
    {

        $sec = trim($sec);

        if(!empty($sec))
        {

            $array = [$array => $sec];

        }
        else if(empty($sec) && is_string($array))
        {

            $array = [$array => ''];

        }

        if(is_array($array) && count($array) > 0)
        {

            foreach ($array as $key => $value)
            {

                if(is_int($key))
                {

                    $key   = $value;

                }

                $regex = implode("",$this->sqlsyntax);
                $ayrac = '';
                if(!strpbrk($regex,$key))
                {

                    $ayrac = ' = ';

                }

                $this->sql["having"]["text"][] = $key .  $ayrac . '? ';
                $this->sql["having"]["value"][] = $value;

            }

        }

        return $this;
    }

    private function select_multiple($select = [],$sec,$mm)
    {

        $sec = trim($sec);

        if(!empty($sec))
        {

            $select = [$select => $sec];

        }
        else if(empty($sec) && is_string($select))
        {

            $select = [$select => $select];

        }

        if(is_array($select) && count($select) > 0)
        {

            foreach ($select as $key => $value)
            {

                if(is_int($key))
                {

                    $key   = $value;

                }

                $this->sql["select"][] = $mm . '(' . $key .') AS '.$value;

            }

        }

        return $this;

    }

    public function from($from)
    {

        if(is_string($from))
        {

            $from = explode(',',$from);

        }
      
        foreach($from as $frm)
        {

           $this->sql["table"][] = $this->prefix . $frm;

        }

        return $this;        

    }

    public function orderby($array = [],$desc = '')
    {

        $desc = trim($desc);

        if(!empty($desc))
        {

            $array = [$array => $desc];

        }
        else if(empty($desc) && is_string($array))
        {

            $array = [$array => ''];

        }

        if(is_array($array) && count($array) > 0)
        {

            foreach ($array as $key => $value)
            {

                if(is_int($key))
                {

                    $key   = $value;
                    $value = '';

                }

                $this->sql["orderby"][]  = trim($key) . ' '. trim($value);
 
            }

        }

        return $this;
        //ORDER BY Country ASC, CustomerName DESC;
        //ORDER BY Country, CustomerName;
        //ORDER BY Country DESC;
        //ORDER BY Country;

    }

    private function group_function($bas = '(' ,$and_or = 'AND')
    {

        $this->sql["where"][] = $and_or;
        $this->sql["where"][] = $bas;

        return $this;

    }

    private function where_combine()
    {
    
        $where = '';

        $this->clearAndOr();

        
        if(count($this->sql["where"]) > 0)
        {

            $where = 'WHERE ' . implode(' ',$this->sql["where"]);

            $where = preg_replace('@\( AND|\( OR@si','(',$where);
            $where = preg_replace('@AND \)|OR \)@si',')',$where);

        }

        if(count($this->sql["groupby"]) > 0)
        {

            $where .= ' GROUP BY ' . implode(',',$this->sql["groupby"]);
         
        }

        if(count($this->sql["having"]["text"]) > 0)
        {

            $where .= ' HAVING ' . implode(',',$this->sql["having"]["text"]);
            $this->sql["value"] = array_merge($this->sql["value"],$this->sql["having"]["value"]);
       
        }

        if(count($this->sql["orderby"]) > 0)
        {

            $where .= ' ORDER BY ' . implode(',',$this->sql["orderby"]);
         
        }

        $where = $this->addPrefix($where);


        if(count($this->sql["limit"]["text"]) > 0)
        {

            $where .= ' ' . implode(' ',$this->sql["limit"]["text"]);
         
            $this->sql["value"] = array_merge($this->sql["value"],$this->sql["limit"]["value"]);

        }

        $this->sql["value"] = array_values($this->sql["value"]);

        return $where;

    }

    private function select_combine()
    {

        $select = 'SELECT ';

        if(count($this->sql["distinct"]) > 0)
        {


           // $select .= 'DISTINCT ';

        }


        if(count($this->sql["select"]) > 0)
        {

            /*if(count($this->sql["distinct"]) > 0)
            {

                $dis = implode(',',$this->sql["distinct"]);

                $select .= (empty($dis) ? '*':$dis) .',';

            }
            */

            $select .= implode(',',$this->sql["select"]);


        }

        $select .= ' FROM '; 

        if(count($this->sql["table"]) > 0)
        {

            $select .= implode(',',$this->sql["table"]);

        }

        if(count($this->sql["join"]) > 0)
        {

            $select .= implode(' ',$this->sql["join"]);

        }

        $select = $this->addPrefix($select);

        return $select;

    }     

    public function limit($min,$max = 0)
    {
        // UPDATE FONKSIYONDA LIMIT 0,20 ŞEKLİNDE ÇALIŞMAZ
        // BÜYÜK İHTİMAL DELETE DE AYNI

        if($max > 0){

            $sql = 'LIMIT ?,?';

            $this->sql["limit"]["text"]["limit"] = $sql;         
            $this->sql["limit"]["value"]["val1"] = $min;
            $this->sql["limit"]["value"]["val2"] = $max;

        }else if($min > 0){

            $sql = 'LIMIT ?';

            $this->sql["limit"]["text"]["limit"] = $sql;
            $this->sql["limit"]["value"]["val1"] = $min;

        }

       
        return $this;

    }

    public function get_sql_select()
    {

        $sql = '';

        if(count($this->sql["table"]) > 0 && ( count($this->sql["select"]) > 0 || count($this->sql["distinct"]) > 0) )
        {

            $where = $this->where_combine();

            $select =  $this->select_combine();

            $sql = $select . ' ' . $where;

        }

        return $sql;

    }

    private function get_result($type = 1)
    {

        $where  = $this->where_combine();
        
        $select =  $this->select_combine();
        
        $sql    = $select . ' ' . $where;

        return $this->pdoexec($sql,$this->sql["value"] , $type);

    }

    private function where_like_function($field,$value,$and_or,$not,$type = '')
    {

        $value = $this->likeEscape($value);

        switch (strtolower($type))
        {
            case 'left' :  $value = '%'.$value;  break;
            case 'right':  $value =  $value.'%';  break;
            
            default     :  $value = '%'.$value.'%'; break;
        }

        if(!empty($field) && !empty($value))
        {

            $this->sql["where"][] = $and_or;
            $this->sql["where"][] = trim($field) . ' ' . $not . ' LIKE ? ';
            $this->sql["value"][] = $value;

        }

        return $this;
    }

    private function where_between_function($field,$one,$two,$and_or,$not = '')
    {
        
        $field = trim($field);
        $one   = trim($one);
        $two   = trim($two);

        if(!empty($field) && !empty($one) && !empty($two))
        {

            $this->sql["where"][] = $and_or;
            $this->sql["where"][] = trim($field) . ' ' . $not . ' BETWEEN ' . $one . ' AND ' . $two;

        }

        return $this;
    }

    private function where_in_function($field,$arr,$three,$not = '')
    {
        
        if(count($arr) > 0)
        {

            $marr = [];

            $this->sql["where"][] = $three;

            foreach($arr as $ar)
            {

                $marr[] = '?';
                $this->sql["value"][] = trim($ar);
                
            }

            $this->sql["where"][] = trim($field) . ' ' . $not . ' IN('.implode(',',$marr).')';

        }

        return $this;
    }

    private function where_function($field,$two,$three,$andor)
    {


        if(is_array($field))
        {

            if(count($field) > 0)
            {

                foreach($field as $key => $value)
                {

                    $this->sql["where"][] = $andor;
                    $this->sql["where"][] = trim($value[0]) . ' ' . ( isset($value[2]) ? $value[1]:'=') . ' ?'; 
                    $this->sql["value"][] = ( isset($value[2]) ? $value[2]:$value[1]);

                }

            }

        }
        else
        {
            
            $this->sql["where"][] = $andor;

            if(in_array($two, $this->sqlsyntax,true))
            {

                $this->sql["where"][] = trim($field) . ' ' . $two . ' ? '; 
                $this->sql["value"][] = ( isset($three) ? $three:$two);

            }
            else
            {

                $this->sql["where"][] = trim($field) . ' = ? '; 
                $this->sql["value"][] = $two;

            }

        }

        return $this;
    }

    public function set($one = [],$two = '',$three = '')
    {

        if(is_array($one))
        {

            if(count($one) > 0)
            {

                foreach ($one as $value)
                {
             
                    $this->sql["set"][] = [
                        "field1" => trim($value[0]),
                        "field2" => (isset($value[2])  ? trim($value[1]) : '?'),
                    ];
                    $this->sql["value"][] = (isset($value[2])  ? trim($value[2]) : trim($value[1]));
                }

            }

        }
        else
        {

            $this->sql["set"][] = [
                "field1" => trim($one),
                "field2" => (!empty($three) ? trim($two) : '?'),
            ];
            $this->sql["value"][] = (!empty($three) ? trim($three) : trim($two));

        }

        return $this;

    }

    public function delete($table)
    {

    	 $table = trim($table);
	    
    	 if(!empty($table))
         {
           
            $where = $this->where_combine();
	
            $sql = 'DELETE FROM '.$this->prefix. trim($table) .'   '.(!empty($where) ? $where:'');

            return $this->pdoexec($sql,$this->sql["value"] , 5);     

         }
    }
	
    public function update($table)
    {

        if(count($this->sql["set"]) > 0)
        {

            $where = $this->where_combine();
            $set =  [];

            if(count($this->sql["set"]) > 0)
            {

                foreach($this->sql["set"] as $up)
                {

                    $set[] = $up["field1"] . ' = ' . $up["field2"];

                }

            }

            $sql = 'UPDATE '.$this->prefix. trim($table) .' SET '.implode(',',$set).' '.(!empty($where) ? $where:'');

            return $this->pdoexec($sql,$this->sql["value"] , 5);       

        }

        return false;

    }


    public function multi_insert($table = '',$field = [] ,$arr = [])
    {

        $sql = [];

        if(count($field) > 0)
        {

            foreach ($field as $value)
            {
            
                $sql[0][] =  trim($value);  

            }

        }

        if(count($arr) > 0)
        {

            foreach ($arr as $value)
            {

                $marray = [];
            
                foreach ($value as $val)
                {
                
                    $marray[] = '?';
                    $sql[1][] = trim($val);


                }

                $sql[2][] =  '(' . implode(',',$marray) . ')';

            }

        }

        if(count($sql[2]) > 0 && count($sql[1]) > 0)
        {

             $sqlstr = "INSERT INTO " . $this->prefix . $table . " (".implode(',',$sql[0]).") VALUES ".implode(',',$sql[2])."";

             $this->pdoexec($sqlstr,$sql[1]);

        }

    }

    public function insert($table = '',$arr = [])
    {

        $sql = [];

        foreach((array)$arr as $key => $value){

            $sql[0][] = trim($key);
            $sql[1][] = '?';
            $sql[2][] = trim($value);

        }

        $sqlstr = "INSERT INTO " . $this->prefix . $table ." (".implode(',',$sql[0]).") VALUES(".implode(',',$sql[1]).")";

        $pre = $this->pdo->prepare($sqlstr);

        $this->pdoexec($sqlstr,$sql[2]);

    }

    private function array_get($table)
    {

        $tablename = [];

        if(is_array($table))
        {

            foreach ($table as $value)
            {
                
                $value = trim($value);

                if(!empty($value))
                {

                    $tablename[] = $this->prefix . $value;

                }

            }

        }
        else
        {
            
            $table = trim($table);

            if(!empty($table))
            {

                $tablename[] = $this->prefix . $table;

            }

        }

        return implode(',',$tablename);        

    }

    public function checksum($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('CHECKSUM TABLE ' . $tablename,[],3);

    }

    public function optimize($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('OPTIMIZE TABLE ' . $tablename,[],3);

    }

    public function repair($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('REPAIR TABLE ' . $tablename,[],3);

    }

    private function clearAndOr()
    {

        if(count($this->sql["where"]) > 0)
        {

            $minimum = min(array_keys($this->sql["where"]));
            $select = strtoupper(trim($this->sql["where"][$minimum]));

            if( in_array($select, $this->and_or) )
            {

                unset($this->sql["where"][$minimum]);

            }

        }

    }

    public function check($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('CHECK TABLE ' . $tablename,[],3);

    }

    private function clearQuery()
    {

        $this->sql = [       
                "select"   => [],
                "from"     => [],
                "where"    => [],
                "value"    => [],
                "set"      => [],
                "orderby"  => [],
                "groupby"  => [],
                "distinct" => [],
                "join"     => [],
                "having"   => [
                    "text"  => [] , 
                    "value" => []
                ],
                "special"   => [ 
                    "text"  => [] , 
                    "value" => []
                ],
                "limit" => ["text" => []],
         ];

    }

    private function likeEscape($str)
    {
  
        return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $str);

    }


}

$db = new PDO_MYSQL([
   'ip' => 'localhost',
   'database' => 'is_test',
   'dbengine' => 'mysql',
   'username' => 'root',
   'password' => '',
   'charset' => 'utf8',
   'prefix' => 'is_'
]);


/*public function not_group_start()
{

    return $this->group_function('(','NOT');

}
*/
  
/*public function or_not_group_start()
{

    return $this->group_function('(','OR NOT');

}
*/