# Feature

### Coming Soon

- [ ] join Function
- [ ] Having Function
- [ ] or_Having Function
- [ ] groupby Function
- [ ] distinct Function
- [ ] max function
- [ ] min function
- [ ] avg function
- [ ] sum function
- [ ] count function
- [ ] group_start function 
- [ ] or_group_start function
- [ ] not_group_start function
- [ ] or_not_group_start function
- [ ] group_end function
- [ ] replace into function

### Completed

- [x] Repair Function
- [x] Optimize Function
- [x] Checksum Function
- [x] Analyze Function
- [x] Insert Function
- [x] Multi Insert Function
- [x] Drop Function
- [x] Empty Table Function
- [x] Truncate Function
- [x] Set function
- [x] where Function
- [x] or_where Function
- [x] Update Function
- [x] where_not_in Function
- [x] where_in Function
- [x] where_or_not_in Function
- [x] where_or_in Function
- [x] between Function
- [x] or_between Function
- [x] not_between Function
- [x] or_not_between Function
- [x] like Function
- [x] or_like Function
- [x] not_like function
- [x] or_not_like function
- [x] Delete Function
- [x] query Function
- [x] Check Function
- [x] get_sql_select
- [x] limit Function
- [x] Select Function
- [x] From Function
- [x] orderby Function
- [x] result_array Function
- [x] result Function
- [x] get_array Function
- [x] get Function

## Install

```php

require "application/database/database.php";

$db = new PDO_MYSQL([
   'ip' => 'localhost',
   'database' => 'is_test',
   'dbengine' => 'mysql',
   'username' => 'root',
   'password' => '',
   'charset' => 'utf8',
   'prefix' => 'is_'
]);

```

### Debug

![alt text](http://i.imgur.com/vldGpuK.png)

#  Documentation

## Speed dial

 * [Special Function](#special-function)
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
 * [Limit](#limit) 
 

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
 * 5 => lastInsertId ( int )
 */
$db->query("SELECT * FROM is_users WHERE id = ?",[22],1);

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

// OPTIMIZE FROM tablename
$db->optimize('TableName');

```

### Repair Table
```php

// REPAIR FROM tablename
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
### Output 

```php
 
result(); // object all
result_array(); // array all
get(); // object one
get_array(); // array one
 
// SELECT username,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->result();

Array
(
    [0] => stdClass Object
        (
            [username] => ismail
            [id] => 1
        )

)

// SELECT username,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->result_array();

Array
(
    [0] => Array
        (
            [username] => ismail
            [0] => ismail
            [id] => 1
            [1] => 1
        )

)

// SELECT username,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->get();

stdClass Object
(
    [username] => ismail
    [id] => 1
)

// SELECT username,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id',2)->orderby('id','ASC')->limit(1)->get_array();

Array
(
    [username] => ismail
    [0] => ismail
    [id] => 1
    [1] => 1
)
```

