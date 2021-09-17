<?php
namespace Seven\JsonDB\Traits;

use Seven\JsonDB\Json;
use Seven\Vars\Strings;
use Seven\JsonDB\Utils\File;

trait Database {

	protected function __construct( 
        protected string $database, protected Json $json
    )
    {
    }

    public function addToSchema(string $table, array $schema): bool
    {
        if( (new File())->append($table, database: $this->database, data: $schema)){
            return true;
        }
        return false;
    }

    /**
    * create table 
    * @param string $table
    * @param array | null $schema : for table fields
    *
    * @example $schema [ 'name', 'username', 'email' ]; id,createdAt & updatedAt are auto-generated
    */

    public function create(string $table, array $schema = []): bool {
        $this->json->create($table);
        if (!empty($schema)) {
            $this->add2Schema($table, ['id', ...$schema, 'createdAt', 'updatedAt']);
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

    public function delete(string $table): bool{
        $this->json->delete($table);
        return true;
    }

}