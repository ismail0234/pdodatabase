<?php 

Class PDO_MYSQL
{

     protected $debugcss = '<style type="text/css">::selection{background-color:#E13300;color:#fff}::-moz-selection{background-color:#E13300;color:#fff}body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}a{color:#039;background-color:transparent;font-weight:400}h1{color:#444;background-color:transparent;border-bottom:1px solid #D0D0D0;font-size:19px;font-weight:400;margin:0 0 14px;padding:14px 15px 10px}code{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #D0D0D0;color:#002166;display:block;margin:14px 0;padding:12px 10px}#container{margin:10px;border:1px solid #D0D0D0;box-shadow:0 0 8px #D0D0D0}p{margin:12px 15px}</style>';

     protected $prefix = '';

     private $pdo = null;

     public function __construct($array = [])
     {

         $connstring = $array["dbengine"].":host=".$array["ip"].";dbname=".$array["database"].";charset=".$array["charset"];

		 try{


			 $this->pdo = new PDO($connstring,$array["username"],$array["password"]);

			 $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			 $this->prefix = $array["prefix"];

		 }catch(Exception $e){

              $this->debug('Database Connection Failed',array(0 => "1049",2 => $e->getMessage()),$connstring);

		 }

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


     public function check($table = [])
     {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('CHECK TABLE ' . $tablename,[],6);

     }

     private function pdoexec($sql,$array = [],$status = 0){

         $pre = $this->pdo->prepare($sql);

         $errorCode = $this->pdo->errorInfo();

         if($errorCode[0] > 0){

            $this->debug('SQL Prepare Error',$errorCode,$sql);
            return false;
         }

         if(!$pre->execute($array))
         {

            $this->debug('SQL Execute Error',$pre->errorInfo(),$sql);
            return false;
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

         return $sonuc;

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

    public function checksum($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('CHECKSUM TABLE ' . $tablename,[],4);

    }

    public function optimize($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('OPTIMIZE TABLE ' . $tablename,[],4);

    }

    public function repair($table = [])
    {

        $tablename = $this->array_get($table);

        if(empty($tablename))
        {

            return false;

        }

        return $this->pdoexec('REPAIR TABLE ' . $tablename,[],4);

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
