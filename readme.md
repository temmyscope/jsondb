## About Seven JsonDB

	=> Simple Php Test JSON Database; useful for development mode

	=> Seven JsonDB is developed by Elisha Temiloluwa a.k.a TemmyScope	

	=> Seven JsonDB is a library that comes with a Validation Class, 
	Arrays Manipulation Class and Strings Generation, Encoding, Sanitization & Manipulation Class


#### Installation
###
```bash
composer require sevens/jsondb
```

### Usage: Seven\JsonDB\JsonDB HOW-TO


***Completely unit tested***

	- Valid Input Sample

```php
$data = [
   'name' => 'Random 1',
   'age' => 24, 'password' => 'gHAST_V43SS',
   'nickname' => 'dick & harry'
];
```

	- Initialization

```php
use Seven\JsonDB\JsonDB;

$db = JsonDB::init('database');

$table = JsonDB::init(database: 'database', table: 'table');

```

	- Make database

*** Use this syntax to create a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::make('database_name'): string;
#returns database name if successfully created
```

	- List all databases

*** Use this syntax to list all available databases ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::list(): array;
#returns an array of databases found
```
	- Count number of databases

*** Use this syntax to count number of available database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::count(): int;
#returns number of databases found
```

	- Delete database

*** Use this syntax to delete a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::delete('database_name'): bool;
#returns true name if successfully deleted
```

	- Empty a database; delete database content

*** Use this syntax to delete all contents from a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::empty('database'): void;
#returns database name if successfully created
```

	- 











