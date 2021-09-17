<?php

namespace Seven\JsonDB\Utils;


class File extends Directory{
    
    protected string $databaseDirectory = __DIR__.'/db/meta/';

    public function __construct() {
    
    }

    public function create(string $name)
    {
        
    }

    public function open(string $name)
    {
        
    }

    public function write(string $name, string $data)
    {
        
    }

    public function appendToSchema(string $table, string $database, array $data)
    {
        $schemaMap = include($this->databaseDirectory.'schema.php');
        $schemaMap[$database][$table] = $data;
        file_put_contents(
            $schemaMap, "<?php return \n" . var_export($schemaMap, true) . ";"
        );
        return true;
    }

    public function append(string $file, array $data)
    {
    
    }

    public function close()
    {
        
    }

}