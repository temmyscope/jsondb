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

$db = JsonDB::init($directory, $database);

$table = JsonDB::init($directory, $database, $tblName);

//OR

$table = $db->setTable($tblName);

```

	- Make database

*** Use this syntax to create a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::make(string $directory, $database): string;
#returns database name if successfully created
```

	- Create Table

*** use this syntax to create a table ***

```php
$schema = [
	'name', 'email', 'password'
];
$table->createTable($table, $schema);

//To use the 'save' method, you need to use a schema when creating a table
```
***Note: id, createdAt and updatedAt are auto-generated fields but you can generate them as well***

	- List all databases

*** Use this syntax to list all available databases ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::list(string $directory): array;
#returns an array of databases found
```
	- Count number of databases

*** Use this syntax to count number of available database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::count(string $directory): int;
#returns number of databases found
```

	- Delete database

*** Use this syntax to delete a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::delete($directory, $db): bool;
#returns true name if successfully deleted
```

	- Empty a database; delete database content

*** Use this syntax to delete all contents from a database ***

```php
use Seven\JsonDB\JsonDB;

$newDB = JsonDB::empty($directory, $db): void;
#returns database name if successfully created
```

### Table Operations

	- Generate an Id

```php
use Seven\JsonDB\Table;

$table->generateId(Table::TYPE_STRING || Table::TYPE_INT);

//default is Table::TYPE_STRING
```

	- Get Last Insert Id

```php
$table->lastInsertId();
```


	- Save Data In the Table: Only works on tables that were created with schema

```php
//$table->id = 1;
$table->name = 'Elisha Temiloluwa';
$table->email = 'Elisha@gmail.com';
$table->password = hash('SHA256', 'password');
$table->save();
```

	- Insert Data In the Table

```php
$table->insert([
	'email' => 'sammy@hotmail.com', 'name' => 'Sam Orji', 
	'password' => hash('SHA256', 'passphr4s3'),
]);
```
	- Find items in the table using certain conditions

```php
$table->find([
	'email' => 'Elisha@gmail.com'
], );
```

	- Find items in the table using id

```php

```
	
	- Find items in the table using certain conditions
	
```php

```

	- Find items in the table using certain conditions

```php

```


