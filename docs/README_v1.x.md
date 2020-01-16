#  Documentation


## Install

```php

// Manual require
require "application/pdo_database.php";

// Vendor require
require  "vendor/autoload.php";

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
    	// Database Debug Type
    	'debugType' => true,
	// Database query log Default: on
	'querylog' => 1,
]);

```

## Query Log
```php

// query log on
'querylog' => 1

// print query log 
// $db->debug returning all sql query ( speed , query , values )
echo "<pre>";
print_r($db->debug);
echo "</pre>";

Output:

Array
(
    [0] => Array
        (
            [sql] => INSERT INTO is_users (username,email) VALUES (?,?),(?,?),(?,?)
            [value] => Array
                (
                    [0] => ismail_satilmis
                    [1] => ismaiil_0234@hotmail.com
                    [2] => ismail_satilmis
                    [3] => ismaiil_0234@hotmail.com
                    [4] => ismail_satilmis
                    [5] => ismaiil_0234@hotmail.com
                )

            [speed] => 0.0016200542449951
        )
)
	
```

## Speed dial
 * [debugOutput Function](#debugoutput-function)
 * [setDebug Function](#setdebug-function)
 * [Special Function](#special-function)
 * [Transaction Function](#transaction-function)
 * [Truncate Table](#truncate-table)
 * [Drop Table](#drop-table)
 * [Empty Table](#empty-table) 
 * [Analyze Table](#analyze-table) 
 * [Checksum Table](#checksum-table) 
 * [Check Table](#check-table) 
 * [Repair Table](#repair-table) 
 * [Insert Data](#insert-data) 
 * [Multi Insert Data](#multi-insert-data) 
 * [Update - set](#set) 
 * [Update - update](#update) 
 * [Delete](#delete) 
 * [Select - select](#select) 
 * [Select - from](#from) 
 * [Select - where](#where) 
 * [Select - or_where](#or_where) 
 * [Select - where_in](#where_in) 
 * [Select - or_where_in](#or_where_in) 
 * [Select - where_not_in](#where_not_in) 
 * [Select - or_where_not_in](#or_where_not_in) 
 * [Select - between](#between) 
 * [Select - or_between](#or_between) 
 * [Select - between_not](#between_not) 
 * [Select - or_between_not](#or_between_not) 
 * [Select - like](#like) 
 * [Select - or_like](#or_like) 
 * [Select - like_not](#like_not) 
 * [Select - or_like_not](#or_like_not) 
 * [Select - orderby](#orderby) 
 * [Select - get_sql_select](#get_sql_select) 
 * [Select - min](#min) 
 * [Select - max](#max) 
 * [Select - count](#count) 
 * [Select - avg](#avg) 
 * [Select - sum](#sum) 
 * [Select - group_start](#group_start---or_group_start---group_end) 
 * [Select - or_group_start](#group_start---or_group_start---group_end) 
 * [Select - group_end](#group_start---or_group_start---group_end) 
 * [Select - having](#having)
 * [Select - join](#join)
 * [Limit](#limit) 
 * [Output](#output) 
 

### debugOutput Function

```php
/*
 * return all sql query html table details speed , query , ms speed
 *
 */ 
$db->debugOutput();

```

### setDebug Function

```php
/*
 * sql query error print type
 * @params false/true
 *         true  => sql query error print exit
 *     false => sql query error throw exception
 */ 
$db->setDebug( false );

```

### Special Function
```php
/*
 * @param sql query
 * @param sql query values
 * @param return type
 * 0 => DEFAULT true/false
 * 1 => fetchAll ( Object )
 * 2 => fetchAll ( Array )
 * 3 => fetch ( Object )
 * 4 => fetch ( Array )
 * 5 => rowcount ( int )
 * 6 => lastInsertId ( int )
 */
$db->query("SELECT * FROM is_users WHERE id = ?",[22],1);

```

### Transaction Function
```php
/*
 * @void started transaction
 */
$db->transactionStart();
/*
 * sql query error throw exception type
 */
$db->setDebug( false );

try {
        
    echo $db->multi_insert('table',["name","surname"],[
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
     *  @void sql querys send commit
     */
    $db->commit();

} catch (Exception $e) {
    
    /*
     *  @void sql querys roll back
     */
    $db->rollBack();

}

```

### Truncate Table 
```php

// TRUNCATE TABLE tablename
$db->truncate('TableName');

```

### Drop Table 
```php

// DROP TABLE tablename
$db->drop('TableName');

```

### Empty Table
```php

// DELETE FROM tablename
$db->empty_table('TableName');

```

### Analyze Table
```php

// ANALYZE TABLE tablename
$db->analyze('TableName');

```

### Checksum Table
```php

// CHECKSUM TABLE tablename
$db->checksum('TableName');

```

### Check Table
```php

// CHECK TABLE tablename
$db->check('TableName');

```

### Optimize Table
```php

// OPTIMIZE TABLE tablename
$db->optimize('TableName');

```

### Repair Table
```php

// REPAIR TABLE tablename
$db->repair('TableName');

```

## Insert


### Insert Data
```php

$arr = [
  'field1' => 'one',
  'field2' => 'two'
];

// INSERT INTO tablename (field1,field2) VALUES(one,two)
$db->insert('TableName',$arr);

```

### Multi Insert Data
```php

$field = [
  'field1',
  'field2'
];

$val = [
  [
    'one',
    'two'
  ],
  [
    'one',
    'two'
  ],
  [
    'one',
    'two'
  ]  
];

// INSERT INTO tablename (field1,field2) VALUES (one,two),(one,two),(one,two)
$db->multi_insert('TableName',$arr,$val);

```

## Update

### set

```php

// SET username = ismail
$db->set('username','ismail');

// SET point = point + 5
$db->set('point', 'point + ?',5);

// SET username = ismail , point = point + 5
$db->set([
    [ "username" , "ismail" ],
    [ "point" , "point + ? " , 5 ],
]);

```

### update

```php

// UPDATE users SET username = ismail
$db->set('username','ismail')->update('users);

// UPDATE users SET point = point - 20 WHERE id = 9
$db->set('point','point - ?',20)->where('id',9)->update('users');
```

## Delete

```php

// DELETE FROM users WHERE id = 9
$db->where('id',9)->delete('users');

// DELETE FROM users WHERE id IN(1,2,3,4) AND point = 20
$db->where_in('id',[1,2,3,4,5])->where('point',20)->delete('users');
```

## Select

### select

```php

// SELECT id , username , email
$db->select('id,username,email');

// SELECT *
$db->select('*');

```

### from

```php

// SELECT id , username , email FROM users
$db->select('id,username,email')->from('users');

// SELECT * FROM users
$db->select('*')->from('users');

```


### where

```php

// SELECT id , username , email FROM users WHERE id = 99
$db->select('id,username,email')->from('users')->where('id','99');

// SELECT * FROM users WHERE id != 99 
$db->select('*')->from('users')->where('id','!=',99);

```

### or_where

```php

// SELECT id , username , email FROM users WHERE id = 99 OR id = 88
$db->select('id,username,email')->from('users')->where('id','99')->or_where('id',88);

// SELECT * FROM users WHERE id != 99 OR id = 99
$db->select('*')->from('users')->where('id','!=',99)->or_where('id',99);

```

### where_in

```php

// SELECT id , username , email FROM users WHERE id IN(1,2,3,4,5)
$db->select('id,username,email')->from('users')->where_in('id',[1,2,3,4,5]);

// SELECT * FROM users WHERE id IN(99) 
$db->select('*')->from('users')->where_in('id',[99]);

```

### or_where_in

```php

// SELECT id , username , email FROM users WHERE id IN(1,2,3,4,5) OR point IN(20,40,60)
$db->select('id,username,email')->from('users')->where_in('id',[1,2,3,4,5])->or_where_in('point',[20,40,60]);

// SELECT * FROM users WHERE id IN(99) OR point IN(20,40,60)
$db->select('*')->from('users')->where_in('id',[99])->or_where_in('point',[20,40,60]);

```

### where_not_in

```php

// SELECT id , username , email FROM users WHERE id NOT IN(1,2,3,4,5)
$db->select('id,username,email')->from('users')->where_not_in('id',[1,2,3,4,5]);

// SELECT * FROM users WHERE id NOT IN(99) 
$db->select('*')->from('users')->where_not_in('id',[99]);

```

### or_where_not_in

```php

// SELECT id , username , email FROM users WHERE id IN(1,2,3,4,5) OR point NOT IN(20,40,60)
$db->select('id,username,email')->from('users')->where_in('id',[1,2,3,4,5])->or_where_not_in('point',[20,40,60]);

// SELECT * FROM users WHERE id IN(99) OR point NOT IN(20,40,60)
$db->select('*')->from('users')->where_in('id',[99])->or_where_not_in('point',[20,40,60]);

```

### between

```php

// SELECT id , username , email FROM users WHERE id BETWEEN 20 AND 40
$db->select('id,username,email')->from('users')->between('id',20,40);

// SELECT * FROM users WHERE point BETWEEN 20 AND 100
$db->select('*')->from('users')->between('point',20,100);

```

### or_between

```php

// SELECT id , username , email FROM users WHERE id = 30 OR id BETWEEN 20 AND 40
$db->select('id,username,email')->from('users')->where('id',30)->or_between('id',20,40);

// SELECT * FROM users WHERE id = 30 OR point BETWEEN 20 AND 100
$db->select('*')->from('users')->where('id',30)->or_between('point',20,100);

```

### between_not

```php

// SELECT id , username , email FROM users WHERE id NOT BETWEEN 20 AND 40
$db->select('id,username,email')->from('users')->between_not('id',20,40);

// SELECT * FROM users WHERE point NOT BETWEEN 20 AND 100
$db->select('*')->from('users')->between_not('point',20,100);

```

### or_between_not

```php

// SELECT id , username , email FROM users WHERE id = 30 OR id NOT BETWEEN 20 AND 40
$db->select('id,username,email')->from('users')->where('id',30)->or_between_not('id',20,40);

// SELECT * FROM users WHERE id = 30 OR point NOT BETWEEN 20 AND 100
$db->select('*')->from('users')->where('id',30)->or_between_not('point',20,100);

```


### like

```php

// SELECT id , username , email FROM users WHERE username LIKE '%ismail%'
$db->select('id,username,email')->from('users')->like('username','ismail','center');

// SELECT * FROM users WHERE username LIKE 'ismail%'
$db->select('*')->from('users')->like('username','ismail','right');

// SELECT * FROM users WHERE username LIKE '%ismail'
$db->select('*')->from('users')->like('username','ismail','left');

```

### or_like

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' OR email LIKE '%ismail%'
$db->select('*')->from('users')->like('username','ismail')->or_like('email','ismail');

```

### like_not

```php

// SELECT * FROM users WHERE username NOT LIKE '%ismail%'
$db->select('*')->from('users')->like_not('username','ismail');

```

### or_like_not

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' OR email NOT LIKE '%ismail%'
$db->select('*')->from('users')->like('username','ismail')->or_like_not('email','ismail');

```

### limit

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20,40);

// SELECT * FROM users WHERE username LIKE '%ismail%' LIMIT 20
$db->select('*')->from('users')->like('username','ismail')->limit(20);

```

### orderby

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id ASC LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20,40)->orderby('id','ASC');

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20,40)->orderby('id');

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id ASC,email DESC LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20,40)->orderby(["id" => "DESC","email" => "ASC"]);

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id ASC,email DESC,point LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20,40)->orderby(["id" => "DESC","email" => "ASC","point"]);

```

### get_sql_select

```php


$db->select('*')->from('users')->where('id',99)->or_like('id',1)->limit(6)->orderby('id')->get_sql_select();

/*
 * PRINT
 * SELECT * FROM is_users WHERE id = ?  OR id  LIKE ? ORDER BY id  LIMIT ?
 */

```

### min

```php

// SELECT MIN(id) AS variable FROM users WHERE id LIKE '%1%'
$db->min('id','variable')->from('users')->like('id',1)->get_array()

// SELECT MIN(id) AS id FROM users WHERE id LIKE '%1%'
$db->min('id')->from('users')->like('id',1)->get_array()

```

### max

```php

// SELECT MAX(id) AS variable FROM users WHERE id LIKE '%1%'
$db->max('id','variable')->from('users')->like('id',1)->get_array()

// SELECT MAX(id) AS id FROM users WHERE id LIKE '%1%'
$db->max('id')->from('users')->like('id',1)->get_array()

```

### count

```php

// SELECT COUNT(id) AS variable FROM users WHERE id LIKE '%1%'
$db->count('id','variable')->from('users')->like('id',1)->get_array()

// SELECT COUNT(id) AS id FROM users WHERE id LIKE '%1%'
$db->count('id')->from('users')->like('id',1)->get_array()

```

### avg

```php

// SELECT AVG(id) AS variable FROM users WHERE id LIKE '%1%'
$db->avg('id','variable')->from('users')->like('id',1)->get_array()

// SELECT AVG(id) AS id FROM users WHERE id LIKE '%1%'
$db->avg('id')->from('users')->like('id',1)->get_array()

```

### sum

```php

// SELECT SUM(id) AS variable FROM users WHERE id LIKE '%1%'
$db->sum('id','variable')->from('users')->like('id',1)->get_array()

// SELECT SUM(id) AS id FROM users WHERE id LIKE '%1%'
$db->sum('id')->from('users')->like('id',1)->get_array()

```

### group_start - or_group_start - group_end 

```php

//SELECT COUNT(id) AS toplam FROM is_users WHERE id = ?  AND ( id = ?  OR ( email = ?  AND ( username = ?  ) ) ) AND point = ? 
$db->count('id','toplam')->from('users')
                ->where('id', 1)
            ->group_start()
                    ->where('id', 1)
                        ->or_group_start()
                                ->where('email', 'ismaiil_0234@hotmail.com')
                            ->group_start()
                                ->where('username', 'ismail_satilmis')
                             ->group_end()
                            ->group_end()
                     ->group_end()
                ->where('point', 0)->get_array()


```

### groupby

```php

// SELECT COUNT(id) AS toplam FROM is_users GROUP BY id,test
$db->select('*')->from('users')->groupby('id')->get_result()

```

### having

```php

// SELECT * FROM is_users  GROUP BY id HAVING id = ? 
$db->select('*')->from('users')->groupby('id')->having('id',1)->get_result()

```

### join

```php

// SELECT is_users.id FROM is_users INNER JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders','orders.uid = users.id','INNER')->where('users.id',1)->get_array()

// SELECT is_users.id FROM is_users LEFT JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders','orders.uid = users.id','LEFT')->where('users.id',1)->get_array()

// SELECT is_users.id FROM is_users RIGHT JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders','orders.uid = users.id','RIGHT')->where('users.id',1)->get_array()

```

### Output
 
```php

result_object(); // object all
result_array(); // array all
get_object(); // object one
get_array(); // array one

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->result_object();

Array
(
    [0] => stdClass Object
        (
            [username] => ismail
            [id] => 2
        )

)

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->result_array();

Array
(
    [0] => Array
        (
            [username] => ismail
            [0] => ismail
            [id] => 2
            [1] => 2
        )

)

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->get_object();

stdClass Object
(
    [username] => ismail
    [id] => 2
)

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->get_array();

Array
(
    [username] => ismail
    [0] => ismail
    [id] => 2
    [1] => 2
)
```
