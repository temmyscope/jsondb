<?php
require __DIR__.'/../src/Database.php';

use PHPUnit\Framework\TestCase;
use Seven\JsonDB\JsonDB;
use Seven\JsonDB\Database;

class DatabaseTest extends TestCase
{

    public function setUp(): void{
        //JsonDB::make('tests');
        $this->database = JsonDB::init('tests');
    }

    public function testTableTransactions()
    {
        $table = $this->database->create('users', [
            'name', 'email', 'password', 'token'
        ]);
        $this->assertTrue($table);

        $tables = $this->database->getTables();
        $this->assertTrue(is_array($tables));
    }

    public function testMeta()
    {
        $metadata = $this->database->metadata();
        //$this->assertSame(is_array($metadata));
        $this->assertTrue(is_array($metadata));
    }

}