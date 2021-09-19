<?php

namespace Seven\JsonDB;

use Seven\JsonDB\Utils\Directory;

class Json extends Directory{

    protected string $pathToDB;

    public function __construct(
        protected string $database, string $directory
    ) {
        parent::setDirectory($directory);
        $this->pathToDB = parent::$dir.$this->database.'/';
    }

    public function createTable(string $table)
    {
        if (
            !file_exists($this->pathToDB.$table.'.json') &&
            file_put_contents($this->pathToDB.$table.'.json', json_encode([]))
        ) {
            return true;
        }
        return false;
    }

    public function appendToSchema(string $database, string $table, array $data)
    {
        $schemaMap = $this->fetchSchema();
        $schemaMap[$database][$table] = $data;
        file_put_contents(
            parent::$dir.'schema.php', 
            "<?php return \n" . var_export($schemaMap, true) . ";"
        );
        return true;
    }

    public function fetchSchema()
    {
        return include(parent::$dir.'schema.php');
    }

    public function flushBase(string $database)
    {
        $schemaMap = $this->fetchSchema();
        if ( isset($schemaMap[$database]) && !empty($schemaMap[$database])) {
            $schemaMap[$database] = [];
        }
        file_put_contents(
            parent::$dir.'schema.php', 
            "<?php return \n" . var_export($schemaMap, true) . ";"
        );
        return true;
    }

    public function deleteBase(string $database)
    {
        $schemaMap = $this->fetchSchema();
        unset($schemaMap[$database]);
        file_put_contents(
            parent::$dir.'schema.php', 
            "<?php return \n" . var_export($schemaMap, true) . ";"
        );
        return true;
    }

    public function deleteTable(string $table)
    {
        $jsonTableFile = $this->pathToDB.$table.'.json';
        unlink($jsonTableFile);

        $schemaMap = $this->fetchSchema();
        unset($schemaMap[$this->database][$table]);
        file_put_contents(
            parent::$dir.'schema.php', 
            "<?php return \n" . var_export($schemaMap, true) . ";"
        );
        return true;
    }

    public function save(string $table, array $data): bool
    {
        $jsonTable = $this->pathToDB.$table.'.json';
        $decodedData = json_decode(
            file_get_contents($jsonTable), true
        );
        $decodedData[] = $data;
        if(file_put_contents($jsonTable, json_encode($decodedData))){
            return true;
        }
        return false;
    }

    public function fetchTables(): array
    {
        $files = glob("{$this->pathToDB}*.json");
        $tables = array_map(
            fn($table) => str_replace('.json', '', $table),
            $files
        );
        return $tables;
    }

    public function fetch(string $table): array | object
    {
        $jsonTableFile = $this->pathToDB.$table.'.json';

        if ( !file_exists($jsonTableFile) ) 
            throw new \Exception("Table '$table' does not exist.", 1);

        return json_decode( file_get_contents($jsonTableFile), true );
    }

}