#  Döküman

## Composer Kurulum

Kurulum için composer kullanmanız gerekmektedir. Composer'a sahip değilseniz windows için [Buradan](https://getcomposer.org/) indirebilirsiniz.

```php

composer require ismail0234/pdodatabase

```

## Kullanım

```php

use IS\QueryBuilder\Database\PdoClient;
use IS\QueryBuilder\Database\Helper\DatabaseException;

include "vendor/autoload.php";

$db = new PdoClient(array(
  // Sunucu Ip Varsayılan: localhost
  'ip'        => 'localhost',
  // Veritabanı Adı
  'database'  => 'databasename',
  // Veritabanı Motoru oracle,mysql ...
  'dbengine'  => 'mysql',
  // Veritabanı Kullanıcı Adı
  'username'  => 'root',
  // Veritabanı Şifresi
  'password'  => '123456',
  // Veritabanı Karakter Seti Varsayılan: utf8
  'charset'   => 'utf8',
  // Veritabanı tablo öneki Varsayılan: null
  'prefix'    => 'is_',
  // Veritabanı hata ayıklama Varsayılan: aktif
  'exception' => true,
  // Veritabanı mysql kalıcı bağlantı zaman aşımı süresi Varsayılan: false
  'persistent' => 30,
  // Veritabanı sorgu logları Varsayılan: pasif
  'querylog'  => false,
));

```

## Speed dial
 * [Veritabanı Kalıcı Bağlantı](#persistent-database-connection)
 * [debugOutput Fonksiyonu](#debugoutput-function)
 * [setException Fonksiyonu](#setexception-function)
 * [Special Fonksiyonu](#special-function)
 * [Transaction Fonksiyonu](#transaction-function)
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
 * [Select - orWhere](#orWhere) 
 * [Select - whereIn](#whereIn) 
 * [Select - orWhereIn](#orWhereIn) 
 * [Select - whereNotIn](#whereNotIn) 
 * [Select - orWhereNotIn](#orWhereNotIn) 
 * [Select - between](#between) 
 * [Select - orBetween](#orBetween) 
 * [Select - betweenNot](#betweenNot) 
 * [Select - orBetweenNot](#orBetweenNot) 
 * [Select - like](#like) 
 * [Select - orLike](#orLike) 
 * [Select - likeNot](#likeNot) 
 * [Select - orLikeNot](#orLikeNot) 
 * [Select - orderby](#orderby) 
 * [Select - getSqlSelect](#getSqlSelect) 
 * [Select - min](#min) 
 * [Select - max](#max) 
 * [Select - count](#count) 
 * [Select - avg](#avg) 
 * [Select - sum](#sum) 
 * [Select - groupStart](#groupStart---orGroupStart---groupEnd) 
 * [Select - orGroupStart](#groupStart---orGroupStart---groupEnd) 
 * [Select - groupEnd](#groupStart---orGroupStart---groupEnd) 
 * [Select - having](#having)
 * [Select - join](#join)
 * [Limit](#limit) 
 * [Response](#response) 
 

### Veritabanı Kalıcı Bağlantı

```php
$db = new PdoClient(array(
	// Mysql Bağlantı Zaman aşımı süresi (saniye)
  'persistent' => 30,
]);

```

### debugOutput Function

```php
/*
 * Bütün sorguların detaylarını ekrana basar. Hız, sorgu , milisaniye hız
 *
 */ 
$db->debugOutput();

```

### setException Function

```php
/*
 * sql exception
 * @params false/true
 *         true  => aktif durumda bir exception fırlatır
 *         false => pasif durumda hata mesajını anlaşılır şekilde ekrana basar.
 */ 
$db->setException(true);

```

### Special Function
```php
/*
 * @param sql sorgusu
 * @param sql sorgu değerleri
 * @param dönüş türü
 * 0 => DEFAULT true
 * 1 => fetchAll ( Object )
 * 2 => fetchAll ( Array )
 * 3 => fetch ( Object )
 * 4 => fetch ( Array )
 * 5 => rowcount ( int )
 * 6 => lastInsertId ( int )
 */
$db->query("SELECT * FROM is_users WHERE id = ?", array(22), 1);

```

### Transaction Function

```php
/*
 * @void transactionı başlat
 */
$db->beginTransaction();

try {
        
    echo $db->bulkInsert('table', array('name', 'surname'), array(
        array('ismail_satilmis', 'ismaiil_0234@hotmail.com'),
        array('ismail_satilmis', 'ismaiil_0234@hotmail.com'),
        array('ismail_satilmis', 'ismaiil_0234@hotmail.com'),      
     ));
     
    /*
     *  @void işlem tamam verileri ekle.
     */
    $db->commit();

} catch (DatabaseException $e) {
    
    /*
     *  @void hata var işlemi geri al
     */
    $db->rollBack();

}

```

### Truncate Table 
```php

// TRUNCATE TABLE tablename
$db->truncateTable('TableName');

```

### Drop Table 
```php

// DROP TABLE tablename
$db->dropTable('TableName');

```

### Empty Table
```php

// DELETE FROM tablename
$db->emptyTable('TableName');

```

### Analyze Table
```php

// ANALYZE TABLE tablename
$db->analyzeTable('TableName');

```

### Checksum Table
```php

// CHECKSUM TABLE tablename
$db->checksumTable('TableName');

```

### Check Table
```php

// CHECK TABLE tablename
$db->checkTable('TableName');

```

### Optimize Table
```php

// OPTIMIZE TABLE tablename
$db->optimizeTable('TableName');

```

### Repair Table
```php

// REPAIR TABLE tablename
$db->repairTable('TableName');

```

## Insert


### Insert Data
```php

$data = array(
  'field1' => 'one',
  'field2' => 'two'
);

// INSERT INTO tablename (field1,field2) VALUES(one,two)
$db->insert('TableName', $data);

```

### Multi Insert Data
```php

$columns = array('field1', 'field2');

$datas = array(
	array('one', 'two'), 
	array('one', 'two'), 
	array('one', 'two'), 
	array('one', 'two') 
);

// INSERT INTO tablename (field1,field2) VALUES (one,two),(one,two),(one,two),(one,two)
$db->bulkInsert('TableName', $columns, $datas);

```

## Update

### set

```php

// SET username = ismail
$db->set('username','ismail');

// SET point = point + 5
$db->set('point', 'point + ?',5);

// SET username = ismail, point = point + 5
$db->set(array(
    array("username", "ismail"),
    array("point", "point + ?", 5)
));

```

### update

```php

// UPDATE users SET username = ismail
$db->set('username', 'ismail')->update('users');

// UPDATE users SET point = point - 20 WHERE id = 9
$db->set('point', 'point - ?', 20)->where('id', 9)->update('users');
```

## Delete

```php

// DELETE FROM users WHERE id = 9
$db->where('id', 9)->delete('users');

// DELETE FROM users WHERE id IN(1,2,3,4) AND point = 20
$db->whereIn('id', array(1, 2, 3, 4, 5))->where('point', 20)->delete('users');
```

## Select

### select

```php

// SELECT id, username, email
$db->select('id, username, email');

// SELECT *
$db->select('*');

```

### from

```php

// SELECT id, username, email FROM users
$db->select('id, username, email')->from('users');

// SELECT * FROM users
$db->select('*')->from('users');

```


### where

```php

// SELECT id, username, email FROM users WHERE id = 99
$db->select('id, username, email')->from('users')->where('id', '99');

// SELECT * FROM users WHERE id != 99 
$db->select('*')->from('users')->where('id', '!=', 99);

```

### orWhere

```php

// SELECT id, username, email FROM users WHERE id = 99 OR id = 88
$db->select('id, username, email')->from('users')->where('id','99')->orWhere('id', 88);

// SELECT * FROM users WHERE id != 99 OR id = 99
$db->select('*')->from('users')->where('id', '!=', 99)->orWhere('id', 99);

```

### whereIn

```php

// SELECT id, username, email FROM users WHERE id IN(1,2,3,4,5)
$db->select('id, username, email')->from('users')->whereIn('id', array(1, 2, 3, 4, 5));

// SELECT * FROM users WHERE id IN(99) 
$db->select('*')->from('users')->whereIn('id', array(99));

```

### orWhereIn

```php

// SELECT id, username, email FROM users WHERE id IN(1,2,3,4,5) OR point IN(20,40,60)
$db->select('id, username, email')->from('users')->whereIn('id', array(1, 2, 3, 4, 5))->orWhereIn('point', array(20, 40, 60));

// SELECT * FROM users WHERE id IN(99) OR point IN(20,40,60)
$db->select('*')->from('users')->whereIn('id', array(99))->orWhereIn('point', array(20, 40, 60));

```

### whereNotIn

```php

// SELECT id, username, email FROM users WHERE id NOT IN(1,2,3,4,5)
$db->select('id, username, email')->from('users')->whereNotIn('id', array(1, 2, 3, 4, 5));

// SELECT * FROM users WHERE id NOT IN(99) 
$db->select('*')->from('users')->whereNotIn('id', array(99));

```

### orWhereNotIn

```php

// SELECT id, username, email FROM users WHERE id IN(1,2,3,4,5) OR point NOT IN(20,40,60)
$db->select('id, username, email')->from('users')->whereIn('id', array(1, 2, 3, 4, 5))->orWhereNotIn('point', array(20, 40, 60));

// SELECT * FROM users WHERE id IN(99) OR point NOT IN(20,40,60)
$db->select('*')->from('users')->whereIn('id', array(99))->orWhereNotIn('point', array(20, 40, 60));

```

### between

```php

// SELECT id, username, email FROM users WHERE id BETWEEN 20 AND 40
$db->select('id, username, email')->from('users')->between('id', 20, 40);

// SELECT * FROM users WHERE point BETWEEN 20 AND 100
$db->select('*')->from('users')->between('point', 20, 100);

```

### orBetween

```php

// SELECT id, username, email FROM users WHERE id = 30 OR id BETWEEN 20 AND 40
$db->select('id, username, email')->from('users')->where('id', 30)->orBetween('id', 20, 40);

// SELECT * FROM users WHERE id = 30 OR point BETWEEN 20 AND 100
$db->select('*')->from('users')->where('id', 30)->orBetween('point', 20, 100);

```

### betweenNot

```php

// SELECT id, username, email FROM users WHERE id NOT BETWEEN 20 AND 40
$db->select('id, username, email')->from('users')->betweenNot('id', 20, 40);

// SELECT * FROM users WHERE point NOT BETWEEN 20 AND 100
$db->select('*')->from('users')->betweenNot('point', 20, 100);

```

### orBetweenNot

```php

// SELECT id, username, email FROM users WHERE id = 30 OR id NOT BETWEEN 20 AND 40
$db->select('id, username, email')->from('users')->where('id', 30)->orBetweenNot('id', 20, 40);

// SELECT * FROM users WHERE id = 30 OR point NOT BETWEEN 20 AND 100
$db->select('*')->from('users')->where('id', 30)->orBetweenNot('point', 20, 100);

```


### like

```php

// SELECT id, username, email FROM users WHERE username LIKE '%ismail%'
$db->select('id, username, email')->from('users')->like('username', 'ismail');

// SELECT id, username, email FROM users WHERE username LIKE '%ismail%'
$db->select('id, username, email')->from('users')->like('username', 'ismail', 'center');

// SELECT * FROM users WHERE username LIKE 'ismail%'
$db->select('*')->from('users')->like('username', 'ismail', 'right');

// SELECT * FROM users WHERE username LIKE '%ismail'
$db->select('*')->from('users')->like('username', 'ismail', 'left');

```

### orLike

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' OR email LIKE '%ismail%'
$db->select('*')->from('users')->like('username', 'ismail')->orLike('email', 'ismail');

```

### likeNot

```php

// SELECT * FROM users WHERE username NOT LIKE '%ismail%'
$db->select('*')->from('users')->likeNot('username', 'ismail');

```

### orLikeNot

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' OR email NOT LIKE '%ismail%'
$db->select('*')->from('users')->like('username', 'ismail')->orLikeNot('email', 'ismail');

```

### limit

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' LIMIT 20
$db->select('*')->from('users')->like('username', 'ismail')->limit(20);

// SELECT * FROM users WHERE username LIKE '%ismail%' LIMIT 20,40
$db->select('*')->from('users')->like('username', 'ismail')->limit(20, 40);

```

### orderby

```php

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20, 40)->orderby('id');

// SELECT * FROM users WHERE username LIKE '%ismail%' ORDER BY id DESC LIMIT 20,40
$db->select('*')->from('users')->like('username','ismail')->limit(20, 40)->orderby('id', 'DESC');

```

### getSqlSelect

```php


echo $db->select('*')->from('users')->where('id', 99)->orLike('id', 1)->limit(6)->orderby('id')->getSqlSelect();

/*
 * PRINT
 * SELECT * FROM users WHERE id = ?  OR id  LIKE ? ORDER BY id  LIMIT ?
 */

```

### min

```php

// SELECT MIN(id) AS variable FROM users WHERE id LIKE '%1%'
$db->min('id', 'variable')->from('users')->like('id', 1)->getArray()

// SELECT MIN(id) AS id FROM users WHERE id LIKE '%1%'
$db->min('id')->from('users')->like('id', 1)->getArray()

```

### max

```php

// SELECT MAX(id) AS variable FROM users WHERE id LIKE '%1%'
$db->max('id', 'variable')->from('users')->like('id', 1)->getArray()

// SELECT MAX(id) AS id FROM users WHERE id LIKE '%1%'
$db->max('id')->from('users')->like('id', 1)->getArray()

```

### count

```php

// SELECT COUNT(id) AS variable FROM users WHERE id LIKE '%1%'
$db->count('id', 'variable')->from('users')->like('id', 1)->getArray()

// SELECT COUNT(id) AS id FROM users WHERE id LIKE '%1%'
$db->count('id')->from('users')->like('id', 1)->getArray()

```

### avg

```php

// SELECT AVG(id) AS variable FROM users WHERE id LIKE '%1%'
$db->avg('id', 'variable')->from('users')->like('id', 1)->getArray()

// SELECT AVG(id) AS id FROM users WHERE id LIKE '%1%'
$db->avg('id')->from('users')->like('id', 1)->getArray()

```

### sum

```php

// SELECT SUM(id) AS variable FROM users WHERE id LIKE '%1%'
$db->sum('id', 'variable')->from('users')->like('id', 1)->getArray()

// SELECT SUM(id) AS id FROM users WHERE id LIKE '%1%'
$db->sum('id')->from('users')->like('id', 1)->getArray()

```

### groupStart - orGroupStart - groupEnd 

```php

//SELECT COUNT(id) AS toplam FROM is_users WHERE id = ?  AND ( id = ?  OR ( email = ?  AND ( username = ?  ) ) ) AND point = ? 
$db->count('id','toplam')->from('users')
                ->where('id', 1)
            ->groupStart()
                    ->where('id', 1)
                        ->orGroupStart()
                                ->where('email', 'ismaiil_0234@hotmail.com')
                            ->groupStart()
                                ->where('username', 'ismail_satilmis')
                             ->groupEnd()
                            ->groupEnd()
                     ->groupEnd()
                ->where('point', 0)->getArray()


```

### groupby

```php

// SELECT COUNT(id) AS toplam FROM is_users GROUP BY id,test
$db->select('*')->from('users')->groupby('id')->getResult()

```

### having

```php

// SELECT * FROM is_users  GROUP BY id HAVING id = ? 
$db->select('*')->from('users')->groupby('id')->having('id', 1)->getResult()

```

### join

```php

// SELECT is_users.id FROM is_users INNER JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders', 'orders.uid = users.id')->where('users.id', 1)->getArray()

// SELECT is_users.id FROM is_users INNER JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders', 'orders.uid = users.id', 'INNER')->where('users.id', 1)->getArray()

// SELECT is_users.id FROM is_users LEFT JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders', 'orders.uid = users.id', 'LEFT')->where('users.id', 1)->getArray()

// SELECT is_users.id FROM is_users RIGHT JOIN is_orders ON is_orders.uid = is_users.id WHERE is_users.id = ?
$db->select('users.id')->from('users')->join('orders', 'orders.uid = users.id', 'RIGHT')->where('users.id', 1)->getArray()

```

### Response
 
```php

resultObject(); // object all
resultArray(); // array all
getObject(); // object one
getArray(); // array one

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id', 2)->orderby('id', 'ASC')->limit(1)->resultObject();

Array
(
    [0] => stdClass Object
        (
            [username] => ismail
            [id] => 2
        )

)

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id', 2)->orderby('id', 'ASC')->limit(1)->resultArray();

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
$db->select('username,id')->from('users')->where('id', 2)->orderby('id', 'ASC')->limit(1)->getObject();

stdClass Object
(
    [username] => ismail
    [id] => 2
)

// SELECT username,id FROM users WHERE id = 2  ORDER BY id ASC LIMIT 1
$db->select('username,id')->from('users')->where('id', 2)->orderby('id', 'ASC')->limit(1)->getArray();

Array
(
    [username] => ismail
    [0] => ismail
    [id] => 2
    [1] => 2
)
```
