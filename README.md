[![Latest Stable Version](https://poser.pugx.org/ismail0234/pdodatabase/v/stable)](https://packagist.org/packages/ismail0234/pdodatabase)
[![Total Downloads](https://poser.pugx.org/ismail0234/pdodatabase/downloads)](https://packagist.org/packages/ismail0234/pdodatabase)
[![Latest Unstable Version](https://poser.pugx.org/ismail0234/pdodatabase/v/unstable)](https://packagist.org/packages/ismail0234/pdodatabase)
[![License](https://poser.pugx.org/ismail0234/pdodatabase/license)](https://packagist.org/packages/ismail0234/pdodatabase)

pdo easy and fast speed framework strong database class.


### Change Log
- See [ChangeLog](https://github.com/ismail0234/pdodatabase/blob/master/CHANGELOG.md)

### Documentation
- See [ChangeLog](https://github.com/ismail0234/pdodatabase/blob/master/docs/README.md)

### License
- See [ChangeLog](https://github.com/ismail0234/pdodatabase/blob/master/LICENSE)


> **IMPORTANT!**  Note: requires ext-pdo: *



## Composer Install

```php

composer require ismail0234/pdodatabase

```

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

### Query Log Output 

![alt text](https://i.imgur.com/gUypSkn.png)


### Debug

![alt text](http://i.imgur.com/vldGpuK.png)
