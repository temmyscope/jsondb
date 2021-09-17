<?php

namespace Seven\JsonDB;

use Seven\JsonDB\{ 
    Database, Json, Table
};

use Seven\JsonDB\Utils\{
    Directory,
};

use \Exception;

class JsonDB {

    /**
     * @param string $database
     * @param string|null $table
     * @example usage: JsonDB::init('doubleh', 'users');
    */

    public static function init(string $database, string | null $table)
    {   
        $json = new Json($database);

        if (!empty($table)) {
            return Table::init($database, $table, $json);
        }
        return Database::init($database, $json);
    }

    public static function list(): array
    {
        return Directory::List();
    }

    public static function count(): int
    {
        return count(static::list());
    }

    /**
    * @param string $name: database name
    * 
    */

    public static function make(string $database): bool
    {
        return Directory::create($database);
    }

    /**
    * @param string $name: database name
    * 
    */

    public static function delete(string $database): bool
    {
        static::empty($database);
        Directory::delete($database);
        return true;
    }

    /**
    * @param string $database
    * 
    * @return void
    */

    public static function empty(string $database): void
    {
        Directory::flush($database);
    }

}