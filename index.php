<?php 

require "application/pdo_database.php";
$db = new pdo_mysql([
    // Server Ip Default: localhost
    'ip'       => 'localhost',
    // Database Name
    'database' => 'is_test',
    // Database Engine Name oracle,mysql ...
    'dbengine' => 'mysql',
    // Database Username
    'username' => 'root',
    // Database Password
    'password' => '',
    // Database Charset Default: utf8
    'charset'  => 'utf8',
    // Database table prefix Default: null
    'prefix'   => 'is_',
    // Database query log Default: on
    'querylog' => 1,
]);
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

print_r($db->from('users')->where('users.id','";TRUNCATE is_users;')->get_array());
echo number_format((microtime(1) - $bas),25,".","");
 print_r($db->debug);

echo "<br>";
?>