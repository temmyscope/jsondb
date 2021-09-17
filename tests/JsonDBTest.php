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
        $dbName = JsonDB::make('test');

        $this->assertSame($dbName, 'test');
    }

    public function testList()
    {
        $databases = JsonDB::list();

        $this->assertTrue(is_array($databases));
    }

    public function testCount()
    {
        $count = JsonDB::count();

        $this->assertTrue(is_numeric($count));
    }

    public function testDelete()
    {
        $deleted = JsonDB::delete('test');
        $this->assertTrue($deleted);
    }
    
}