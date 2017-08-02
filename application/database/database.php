<?php 

Class PDO_MYSQL
{

     protected $debugcss = '<style type="text/css">::selection{background-color:#E13300;color:#fff}::-moz-selection{background-color:#E13300;color:#fff}body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}a{color:#039;background-color:transparent;font-weight:400}h1{color:#444;background-color:transparent;border-bottom:1px solid #D0D0D0;font-size:19px;font-weight:400;margin:0 0 14px;padding:14px 15px 10px}code{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #D0D0D0;color:#002166;display:block;margin:14px 0;padding:12px 10px}#container{margin:10px;border:1px solid #D0D0D0;box-shadow:0 0 8px #D0D0D0}p{margin:12px 15px}</style>';


     protected $selectarr = array();
     
     protected $whereand = array();

     protected $whereor = array();

     protected $orderby = array();

	 protected $from = null;

     protected $where = array("var" => [],"sql" => "");

     protected $limit = array();

     protected $prefix = '';

     private $pdo = null;

     public function __construct(){
		 global $fw_config;

         $connstring = $fw_config["database"]["dbengine"].":host=".$fw_config["database"]["ip"].";dbname=".$fw_config["database"]["database"].";charset=".$fw_config["database"]["charset"];

		 try{


			 $this->pdo = new PDO($connstring,$fw_config["database"]["username"],$fw_config["database"]["password"]);

			 $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
//             $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			 $this->prefix = $fw_config["database"]["prefix"];

		 }catch(Exception $e){

              $this->debug('Database Connection Failed',array(0 => "1049",2 => $e->getMessage()),$connstring);

		 }

	 }
     

     public function select($s = '*'){

          if(is_string($s)){

          		$sarr = explode(',',$s);

          }
          
          foreach($sarr as $sar){

               $this->selectarr[] = $sar;

          }

          return $this;
     }

     public function from($fr){

 		 $this->from = $fr;
         
         return $this;

     }

     public function or_where($arr = []){
        
        return $this->where_in_where($arr,'OR');

     }

     public function where($arr = []){
        
        return $this->where_in_where($arr,'AND');
 		 
     }


     private function where_in_where($arr,$ara = 'AND',$isaret = '='){
      
 		  $ara = trim($ara);

	      switch ($ara) {
	      	case 'OR':
			         foreach((array)$arr as $key => $value){
			     		$this->whereor[$key]["val"] = $value;
			     		$this->whereor[$key]["tur"] = $isaret;
			         }
	      		break;
	      	
	      	default:
 			         foreach((array)$arr as $key => $value){
			     		$this->whereand[$key]["val"] = $value;
			     		$this->whereand[$key]["tur"] = $isaret;

			         }
      		break;
	      }
        
        return $this;

     }
     
     private function where_combine($arr,$isaret = 'AND'){

    	$sql = [];


        if(!empty($this->where["sql"])){

	        $sql[] = $this->where["sql"];

        }
        if(count($arr) >  0){
            foreach((array)$arr as $key => $value){
                
                if(empty($key)){

                    continue;
                }

                $sql[] = $key.' '.$value["tur"].' ?';
                $this->where["var"][] = $value["val"];

            }  
        }
  	

        if(count($sql) > 0){

            $this->where["sql"] = implode(' '.$isaret.' ', $sql);

        }

     }

     public function limit($min,$max = 0){

        $this->limit = array($min,$max);
   
   		return $this;
     }
     
     private function limit_combine($arr){

        $sql = '';
        $var = [];

        if(isset($arr[1]) && $arr[1] > 0){

            $sql = 'LIMIT ?,?';
            $var = $arr;

        }else if(count($arr) > 0){

            $sql = 'LIMIT ?';
            $var = array($arr[0]);


        }

        return array('sql' => $sql, 'var' => $var); 


     }

     private function debug($name,$arr = [],$sql){

       echo $this->debugcss;

       echo '<div id="container"><h1>'.$name.'</h1>';
       echo '<p> Error Number: '.(isset($arr[0]) ? $arr[0]:'-');
       echo '<p> '.(isset($arr[2]) ? $arr[2]:'-');
       echo '<p> '.(isset($sql) ? $sql:'-');
       echo '</div>';

       exit;
     }


     public function insert($table = '',$arr = []){

     	 $sql = [];

     	 foreach((array)$arr as $key => $value){

     	 	$sql[0][] = htmlspecialchars($key);
     	 	$sql[1][] = '?';
     	 	$sql[2][] = htmlspecialchars($value);

     	 }

         $sqlstr = "INSERT INTO ".$this->prefix.$table." (".implode(',',$sql[0]).") VALUES(".implode(',',$sql[1]).")";

     	 $pre = $this->pdo->prepare($sqlstr);

         $this->pdoexec($sqlstr,$sql[2]);


     }
     public function insert_batch($table = '',$arr = []){

     	 $sql = [];
         $i = 0;
     	 foreach((array)$arr as $k => $v){
               $sql2 = [];
     	 	   $str = '(';
	           foreach((array)$v as $key => $value){

		     	 	if(!$i){
		     	 		$sql[0][] =  htmlspecialchars($key);
		     	 	}
		     	 	$sql2[] = '?';
		     	 	$sql[2][] = htmlspecialchars($value);

	           }
	           $str .= implode(',',$sql2).')';
	           $sql[1][] = ($str);
               $i++;
     	 }

     	 $sqlstr = "INSERT INTO ".$this->prefix.$table." (".implode(',',$sql[0]).") VALUES ".implode(',',$sql[1])."";

         $this->pdoexec($sqlstr,$sql[2]);
     }


     private function pdoexec($sql,$array,$status = 0){

         $pre = $this->pdo->prepare($sql);

         $errorCode = $this->pdo->errorInfo();
         if($errorCode[0] > 0){

            $this->debug('SQL Prepare Error',$errorCode,$sql);
            return false;
         }

         if(!$pre->execute($array)){

            $this->debug('SQL Execute Error',$pre->errorInfo(),$sql);
            return false;
         }

         $sonuc = true;

         switch($status){

            case 1: $sonuc = $pre->fetchAll(PDO::FETCH_OBJ);  break;
            case 2: $sonuc = $pre->fetchAll();  break;
            case 3: $sonuc = $pre->fetch(PDO::FETCH_OBJ);  break;
            case 4: $sonuc = $pre->fetch();  break;
            case 5: $sonuc = $pre->rowcount();  break;
            case 6: $sonuc = $this->pdo->lastInsertId();  break;

         }

         $this->clearQuery();

         return $sonuc;

     }

     public function truncate($table){

           $this->pdoexec('TRUNCATE '.htmlspecialchars($table).'',array());

     }

     public function drop($table){

           $this->pdoexec('DROP TABLE '.htmlspecialchars($table).'',array());

     }

     public function empty_table($table){

           $this->pdoexec('DELETE FROM '.htmlspecialchars($table).'',array());

     }

     public function orderby($arr,$desasc = null){

        if($desasc != null){

            $this->orderby[] = $arr . ' ' . $desasc;
            return $this;

        }

        $arr = explode(',',$arr);
        if(count($arr) > 0){

            foreach($arr as $ar){

               $this->orderby[] = $ar;
             
            }

        }

        return $this;

     }
     
     private function  where_combine_combine($select = 0){

         $arr = []; $orderby = '';

         $this->where_combine($this->whereand,'AND');

         $this->where_combine($this->whereor,'OR');
         
         $limit = $this->limit_combine($this->limit);
         

         if($select = 1){

            if(count($this->orderby) > 0){

                $orderby = 'ORDER BY '.implode(',',$this->orderby);

            }

         }

         if(count($this->where["var"]) > 0){

             $arr = array_merge($arr,$this->where["var"]);

         }
         if(count($limit["var"]) > 0){

             $arr = array_merge($arr,$limit["var"]);

         } 
         return array("sql" => $this->where["sql"].' '.$orderby.' '.$limit["sql"],"var" => $arr);
     }

     private function select_combine($where){

         return 'SELECT '.implode(',',$this->selectarr).' FROM '.$this->from.'  '.(empty(trim($where)) ? '':'WHERE '.$where);

     }

     private function clearQuery(){

        $this->selectarr = array();
        $this->from = null;
        $this->where = array();
        $this->whereand = array();
        $this->whereor = array();
        $this->limit = array();
        $this->orderby = array();

     }

     public function result(){  // object all

         $where = $this->where_combine_combine(1);

		 $sql =  $this->select_combine($where["sql"]);

        return $this->pdoexec($sql,$where["var"] , 1);
     }
     public function result_array(){ // array all

         $where = $this->where_combine_combine(1);
         $sql =  $this->select_combine($where["sql"]);

        return $this->pdoexec($sql,$where["var"] , 2);
     }
     public function get(){ // object one

         $where = $this->where_combine_combine(1);

         $sql =  $this->select_combine($where["sql"]);

        return $this->pdoexec($sql,$where["var"] , 3);
     }
     public function get_array(){ // array one

         $where = $this->where_combine_combine(1);

         $sql =  $this->select_combine($where["sql"]);

        return $this->pdoexec($sql,$where["var"] , 4);
     }     
     public function isimDuzelt($str){

    $basharf = $str[0];

    $soncikti = (($basharf)) . substr(mb_strtolower($str),1,(strlen($str) - 1));

    return $soncikti;
}

}
$db = new PDO_MYSQL;
