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
 $db->insert('users',[
 	'username' => 'ismail_satilmis',
 	'email' => 'ismaiil_0234@hotmail.com',
 ]);
/*
 * MULTI INSERT DATA
 * @param $tablename
 * @param array fieldone , fieldtwo ...
 * @param array array valueone , valuetwo ...
 */
 $db->multi_insert('users',["username","email"],[
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
// print_r($db->analyze('users'));	
// echo "</pre>";
 /*
 * CHECKSUM TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// print_r($db->checksum('users'));	
// echo "</pre>";
 /*
 * OPTIMIZE TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// print_r($db->optimize('users'));	
// echo "</pre>";
 /*
 * REPAIR TABLE
 * @param $tablename
 * 
 */
// echo "<pre>";
// print_r($db->repair('users'));	
// echo "</pre>";
/*
    $db->set('username','ismail');
    $db->set('point', 'point + ?',5);
    $db->set([
        [ "username" , "ismail" ],
        [ "point" , "point + ? " , 5 ],
    ]);

*/
     $db->set([
        [ "username" , "weqew" ],
    ])->where_in('id',[1,2,3,4,5,6,7,8,9])->update('users');

 echo "<pre>";
 print_r($db->sql["where"]);
?>