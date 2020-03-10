[![Latest Stable Version](https://poser.pugx.org/ismail0234/pdodatabase/v/stable)](https://packagist.org/packages/ismail0234/pdodatabase)
[![Total Downloads](https://poser.pugx.org/ismail0234/pdodatabase/downloads)](https://packagist.org/packages/ismail0234/pdodatabase)
[![License](https://poser.pugx.org/ismail0234/pdodatabase/license)](https://packagist.org/packages/ismail0234/pdodatabase)

pdo easy and fast speed framework strong database class.


### Change Log
- See [ChangeLog](https://github.com/ismail0234/pdodatabase/blob/master/CHANGELOG.md)

### Documentation
- See [Documentation v1.x - English](https://github.com/ismail0234/pdodatabase/blob/master/docs/README_v1.x.md)
- See [Documentation v2.x - English](https://github.com/ismail0234/pdodatabase/blob/master/docs/README_v2.x.md)
- See [Documentation v2.x - Türkçe](https://github.com/ismail0234/pdodatabase/blob/master/docs/README_v2.x_TR.md)

### License
- See [License](https://github.com/ismail0234/pdodatabase/blob/master/LICENSE)


> **IMPORTANT!**  Note: requires ext-pdo: *



## Composer Install

```php

composer require ismail0234/pdodatabase

```

## Install

```php

use IS\QueryBuilder\Database\PDOClient;

include "vendor/autoload.php";

$db = new PDOClient(array(
	// Server Ip Default: localhost
	'ip'        => 'localhost',
	// Database Name
	'database'  => 'databasename',
	// Database Engine Name oracle,mysql ...
	'dbengine'  => 'mysql',
	// Database Username
	'username'  => 'root',
	// Database Password
	'password'  => '123456',
	// Database Charset Default: utf8
	'charset'   => 'utf8',
	// Database table prefix Default: null
	'prefix'    => 'is_',
	// Database exception Default: on
	'exception' => true,
	// Database persistent connection timeout Default: false
	'persistent' => 30,
	// Database query log Default: off
	'querylog'  => false,
));

```

## Query Log
```php


$db->debugOutput();

Output:
```

### Query Log Output 

![alt text](https://i.imgur.com/gUypSkn.png)


### Debug

![alt text](http://i.imgur.com/vldGpuK.png)
