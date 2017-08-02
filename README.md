
#  Documentation

### Include File 
```php

require "application/config/config.php";
require "application/database/database.php";



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

$arr = [
  ['field1' => 'one','field2' => 'two'],
  ['field1' => 'three','field2' => 'four'],
  ['field1' => 'five','field2' => 'six']
];

// INSERT INTO tablename (field1,field2...) VALUES(one,two),(three,four),(five,six)
$db->insert_batch('TableName',$arr);

```


### Select Sql Query

### Select

```php

// SELECT id
$db->select('id');
// SELECT id,uid
$db->select('id,uid');
// SELECT id,uid
$db->select('id')->select('uid');
// SELECT *
$db->select('*');

```
### From

```php

// SELECT id FROM users
$db->select('id')->from('users');

```

### where

```php

// SELECT * FROM users WHERE uid = 2 AND name = user
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"]);

```


### where_or

```php

// SELECT * FROM users WHERE uid = 2 OR name = user
$db->select('*')->from('users')->where_or(["uid" => 2 , "name" => "user"]);

```

### ORDER BY

```php

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id','ASC');

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id ASC');

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC,uid DESC
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id ASC,uid DESC');

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC,uid DESC
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id','ASC')->orderby('uid','DESC');
```


### LIMIT

```php

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC LIMIT 0,30
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id','ASC')->limit(0,30);

// SELECT * FROM users WHERE uid = 2 AND name = user ORDER BY id ASC LIMIT 10
$db->select('*')->from('users')->where(["uid" => 2 , "name" => "user"])->orderby('id','ASC')->limit(10);
```

### Output

```php

result(); // object all
result_array(); // array all
get(); // object one
get_array(); // array one

// SELECT uid,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('uid,id')->from('users')->where(["uid" => 2 ])->orderby('id','ASC')->limit(1)->result();

Array
(
    [0] => stdClass Object
        (
            [uid] => 2
            [id] => 1
        )

)

// SELECT uid,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('uid,id')->from('users')->where(["uid" => 2 ])->orderby('id','ASC')->limit(1)->result_array();

Array
(
    [0] => Array
        (
            [uid] => 2
            [0] => 2
            [id] => 1
            [1] => 1
        )

)

// SELECT uid,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('uid,id')->from('users')->where(["uid" => 2 ])->orderby('id','ASC')->limit(1)->get();

stdClass Object
(
    [uid] => 2
    [id] => 1
)

// SELECT uid,id FROM users WHERE uid = 2  ORDER BY id ASC LIMIT 1
$db->select('uid,id')->from('users')->where(["uid" => 2 ])->orderby('id','ASC')->limit(1)->get_array();

Array
(
    [uid] => 2
    [0] => 2
    [id] => 1
    [1] => 1
)
```
