<?php
require __DIR__.'/../src/JsonDB.php';

use PHPUnit\Framework\TestCase;
use Seven\JsonDB\JsonDB;

class JsonDBTest extends TestCase
{
    public function setUp(): void{
        //nothing to setup
    }

    public function testMake()
    {
        $dbName = JsonDB::make(__DIR__.'/../db', 'tests');
        
        $this->assertSame(true, is_bool($dbName));
    }

    public function testList()
    {
        $databases = JsonDB::list(directory: __DIR__.'/../db');

        $this->assertTrue(is_array($databases));
    }

    public function testCount()
    {
        $count = JsonDB::count(directory: __DIR__.'/../db');

        $this->assertTrue(is_numeric($count));
    }

    public function testDelete()
    {
        //$deleted = JsonDB::delete('tests', directory: __DIR__.'/../db');
        //$this->assertTrue($deleted);
    }
    
}