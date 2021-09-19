<?php

require __DIR__.'/../src/Table.php';

use PHPUnit\Framework\TestCase;
use Seven\JsonDB\{JsonDB, Table};

class TableTest extends TestCase
{
    public function setUp(): void {
        $this->database = JsonDB::init(
            directory: __DIR__.'/../db', database:'tests'
        );
        $this->table = $this->database->setTable('users');
    }
    /*
    public function testSave()
    {
        //$this->table->id = 1;
        $this->table->name = 'Elisha Temiloluwa';
        $this->table->email = 'Esdentp@gmail.com';
        $this->table->password = hash('SHA256', 'password');
        $this->table->token = 'R4ND0Mt0k3n';
        $id = $this->table->save();
        $this->assertTrue(is_string($id));
    }*/

    public function testMisc()
    {
        $id = $this->table->lastInsertId();

        $newIntId = $this->table->generateId(Table::TYPE_INT);
        $newStringId = $this->table->generateId(Table::TYPE_INT);

        $time = Table::generateTime();
        $this->assertTrue(is_string($time));
        var_dump($id);
        $this->assertTrue(!empty($id));
        $this->assertTrue(is_int($newIntId));
        $this->assertTrue(is_string($newStringId));
    }

    public function testFetch()
    {
        $res = $this->table->search([
            'email' => 'Esdentp@gmail.com'
        ]);
        var_dump($res);
        $one = $this->table->findOne([
            'email' => 'Esdentp@gmail.com'
        ]);
        $last = $this->table->last([
            'email' => 'Esdentp@gmail.com'
        ]);
        $all = $this->table->find([
            'email' => 'Esdentp@gmail.com'
        ]);
        $first = $this->table->first([
            'email' => 'Esdentp@gmail.com'
        ]);
        $oneById = $this->table->findById('38a92f92b1268c64');
        $this->assertTrue(is_array($res));
        $this->assertTrue(is_array($one));
        $this->assertTrue(is_array($last));
        $this->assertTrue(is_array($all));
        $this->assertTrue(is_array($first));
        $this->assertTrue(is_array($oneById));
        $this->assertTrue(empty($oneById));
    }
    
}