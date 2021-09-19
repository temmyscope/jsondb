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
    
    public function testModifiers()
    {
        //$this->table->id = 1;
        $this->table->name = 'Elisha Temiloluwa';
        $this->table->email = 'Esdentp@gmail.com';
        $this->table->password = hash('SHA256', 'password');
        $this->table->token = 'R4ND0Mt0k3n';
        $id = $this->table->save();
        $this->assertTrue(is_string($id));

        $this->assertTrue(is_string($this->table->insert([
            'email' => 'sammy@hotmail.com', 'name' => 'Sam Orji',
            'password' => hash('SHA256', 'passphr4s3'), 'token' => '3ncrypt3d'
        ])));

        $this->assertTrue(
            is_array(
                $this->table->update(
                    ['token' => 'D3crypt3d'], 
                    ['email' => 'ajlee@gmail.com']
                )
            )
        );
    }

    public function testMisc()
    {
        $id = $this->table->lastInsertId();

        $newIntId = $this->table->generateId(Table::TYPE_INT);
        $newStringId = $this->table->generateId(Table::TYPE_STRING);

        $this->assertTrue(is_string(Table::generateTime()));
        $this->assertTrue(!empty($id));
        $this->assertTrue(is_int($newIntId));
        $this->assertTrue(is_string($newStringId));
    }

    public function testFetch()
    {
        $res = $this->table->search([
            'email' => 'Esdentp@gmail.com'
        ]);
        $one = $this->table->findOne([
            'email' => 'Esdentp@gmail.com'
        ]);
        $last = $this->table->last([
            'email' => 'Esdentp@gmail.com'
        ]);
        $all = $this->table->find([
            'email' => 'esdentp@gmail.com'
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
        $this->assertTrue(is_object($oneById) || is_array($oneById));
    }
    
}