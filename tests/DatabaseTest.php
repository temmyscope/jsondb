<?php
require __DIR__.'/../src/Database.php';

use PHPUnit\Framework\TestCase;
use Seven\JsonDB\JsonDB;
use Seven\JsonDB\Database;

class DatabaseTest extends TestCase
{

    public function setUp(): void{
        $this->database = JsonDB::init(__DIR__.'/../db', 'tests');
    }

    public function testTableTransactions()
    {
        $table = $this->database->createTable('users', [
            'name', 'email', 'password', 'token'
        ]);

        $this->assertTrue($table);

        $tables = $this->database->getTables();

        $this->assertTrue(is_array($tables));

        //$deleted = $this->database->deleteTable('users');
        //$this->assertTrue($deleted);

        $metadata = $this->database->metadata();
        $this->assertTrue(is_array($metadata));
    }

}