<?php

namespace Tests\Schema;

use Paprika\Schema\SchemaFactory;
use PHPUnit\Framework\TestCase;

class SchemaFactoryTest extends TestCase
{
    public function testCreateV01()
    {
        $factory = new SchemaFactory();
        $schema = $factory->createV01();

        $this->assertTrue(count($schema->getDataObjects()) > 0);

        // assert has schema 00-99
        foreach (range(00, 99) as $id) {
            $id = str_pad($id, 2, '0', STR_PAD_LEFT);
            $this->assertNotNull($schema->getById($id));
        }
    }
}
