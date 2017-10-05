
#  Documentation

### Include File 
```php

require "application/database/database.php";

```
### Debug

![alt text](http://i.imgur.com/vldGpuK.png)


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
