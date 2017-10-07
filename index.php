<?php 

require "application/database/database.php";

/*
 * DROP TABLE
 * @param $tablename
 * return true or false
 */
 //$db->drop('users');

/*
 * INSERT DATA
 * @param $tablename
 * @param array fieldone => value ...
 * 
 */
 //$db->truncate('users');
/*
 $db->insert('users',[
 	'username' => 'ismail_satilmis',
 	'email' => 'ismaiil_0234@hotmail.com',
 ]);
 */
/*
 * MULTI INSERT DATA
 * @param $tablename
 * @param array fieldone , fieldtwo ...
 * @param array array valueone , valuetwo ...
 */
 /*$db->multi_insert('users',["username","email"],[
 	[
 		'ismail_satilmis',
 		'ismaiil_0234@hotmail.com',
 	],
 	[
 		'ismail_satilmis',
 		'ismaiil_0234@hotmail.com',
 	],
 	[
 		'ismail_satilmis',
 		'ismaiil_0234@hotmail.com',
 	], 	 	
 ]);
*/
/*
 * EMPTY TABLE
 * @param $tablename
 * 
 */
// $db->empty_table('users');


/*
 * TRUNCATE TABLE
 * @param $tablename
 * 
 */
 //$db->truncate('users');

 /*
 * ANALYZE TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// ($db->analyze('users'));	
// echo "</pre>";
 /*
 * CHECKSUM TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// ($db->checksum('users'));	
// echo "</pre>";
 /*
 * OPTIMIZE TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// ($db->optimize('users'));	
// echo "</pre>";
 /*
 * REPAIR TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// ($db->repair('users'));	
// echo "</pre>";
/*
    $db->set('username','ismail');
    $db->set('point', 'point + ?',99);
    $db->set([
        [ "username" , "ismail" ],
        [ "point" , "point + ? " , 99 ],
    ]);


     $db->set([
        [ "username" , "weqewxxx" ],
    ])->between('point',10,100)->limit(100)->update('users');
     */
     /*
 echo "<pre>";
 */


echo "<pre>";
  $bas = microtime(1);

print_r($db->select('users.id')->from('users')->join('orders','orders.uid = users.id','RIGHT')->where('users.id',1)->get_sql_select());
echo number_format((microtime(1) - $bas),25,".","");
 print_r($db->debug);

echo "<br>";
?>