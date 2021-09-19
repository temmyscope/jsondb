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
     * @param string $directory
     * @param string $database
     * @param string|null $table
     * @example usage: JsonDB::init('doubleh', 'users');
    */

    public static function init(string $directory, string $database, string | null $table = '')
    {
        $json = new Json($database, $directory);
        if (!empty($table)) {
            return Table::init($database, $table, $json);
        }
        return Database::init($database, $json);
    }

    public static function list(string $directory): array
    {
        Directory::setDirectory($directory);
        return Directory::List();
    }

    public static function count(string $directory): int
    {
        Directory::setDirectory($directory);
        return count(static::list($directory));
    }

    /**
    * @param string $name: database name
    * 
    */

    public static function make(string $directory, string $database): bool
    {
        return (Directory::setDirectory($directory))->create($database);
    }

    /**
    * @param string $name: database name
    * 
    */

    public static function delete(string $database, string $directory): bool
    {
        return (
            static::empty($database, $directory) && 
            (Directory::setDirectory($directory))->delete($database)
        );
    }

    /**
    * @param string $database
    * 
    * @return void
    */

    public static function empty(string $database, string $directory): bool
    {
        return (Directory::setDirectory($directory))->flush($database);
    }

}