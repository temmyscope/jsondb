<?php

namespace Seven\JsonDB;

class Json {

    protected string $directory = __DIR__.'/db/meta/';

    protected string $database;

    public function __construct(
        string $databaseName
    ) {
        $this->database = $this->directory.$databaseName.'/';
    }

    public function create(string $table): bool
    {
        if (!file_exists($this->database.$table.'.json')) {
            if($this->save($table, $data = [])){
                return true;
            }
        }
        return false;
    }

    public function save(string $table, array $data): bool
    {
        $encodedData = json_encode($data);

        $jsonTable = $this->database.$table.'.json';

        if(file_put_contents($jsonTable, $encodedData, FILE_APPEND)){
            return true;
        }
        return false;
    }

    public function fetchTables(): array
    {
        $files = glob("{$this->database}*.json");

        $tables = array_map(
            fn($table) => str_replace('.json', '', $table),
            $files
        );
        
        return $tables;
    }

    public function fetch(string $table): array | object
    {
        $jsonTableFile = $this->database.$table.'.json';

        if ( !file_exists($jsonTableFile) ) 
            throw new \Exception("Table '$table' does not exist.", 1);

        $tableData = file_get_contents($jsonTableFile);

        $parsedData = json_decode($tableData);

        return $parsedData;
    }

    public function delete(string $table)
    {
        $jsonTableFile = $this->database.$table.'.json';
        unlink($jsonTableFile);
    }

}