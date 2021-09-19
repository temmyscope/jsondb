<?php
namespace Seven\JsonDB\Traits;

use Seven\JsonDB\{Json, Table};
use Seven\Vars\Strings;

trait Database {

	protected function __construct( 
        protected string $database, protected Json $json
    )
    {
    }

    public function setTable(string $table): Table
    {
        return Table::init($this->database, $table, $this->json);
    }

    /**
    * create table 
    * @param string $table
    * @param array | null $schema : for table fields
    *
    * @example $schema [ 'name', 'username', 'email' ]; id,createdAt & updatedAt are auto-generated
    */

    public function createTable(string $table, array $schema = []): bool {
        $this->json->createTable($table);
        if (!empty($schema)) {
            $this->json->appendToSchema(
                database: $this->database, table: $table,
                data: ['id', ...$schema, 'createdAt', 'updatedAt']
            );
        }
        return true;
    }

    public function getTables(): array {
        return $this->json->fetchTables();
    }

    public function metadata(): array {
        $tables = $this->getTables();

        return [
            'database' => $this->database,
            'tables' => $tables,
            'queriedAt' => Strings::timeFromString('now'),
            'numberOfTables' => count($tables) 
        ];
    }

    public function deleteTable(string $table): bool{
        $this->json->deleteTable($table);
        return true;
    }

}