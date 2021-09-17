<?php

require __DIR__.'/../src/Table.php';

use PHPUnit\Framework\TestCase;
use Seven\JsonDB\JsonDB;

class TableTest extends TestCase
{
    public function setUp(): void {
        JsonDB::make('tests');
        $this->table = JsonDB::init('tests');
    }

    public function testSave()
    {
        
    }
    
}