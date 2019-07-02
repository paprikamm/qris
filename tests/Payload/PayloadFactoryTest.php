<?php

namespace Tests\Schema;

use Paprika\Payload\PayloadFactory;
use Paprika\Schema\SchemaFactory;
use PHPUnit\Framework\TestCase;

class PayloadFactoryTest extends TestCase
{
    public function testCodeNotBeginWith00()
    {

    }

    public function testCodeNotEndsWithCRC()
    {

    }

    public function testWrongFormat()
    {
        $factory = new PayloadFactory();
    }

    public function testMandatoriesNotIncluded()
    {

    }

    public function testWrongLength()
    {

    }

    public function testWrongAmount()
    {

    }

    public function testWrongAmountFormat()
    {

    }

    public function testConvenience()
    {

    }

    public function testSuccess()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertTrue($result->isValid());
        $this->assertNotNull($result->getById('00'));
        $this->assertNotNull($result->getById('01'));
        $this->assertNotNull($result->getById('02'));
        $this->assertNotNull($result->getById('04'));
        $this->assertNotNull($result->getById('26'));
        $this->assertNotNull($result->getById('27'));
        $this->assertNotNull($result->getById('51'));
        $this->assertNotNull($result->getById('52'));
        $this->assertNotNull($result->getById('53'));
        $this->assertNotNull($result->getById('58'));
        $this->assertNotNull($result->getById('59'));
        $this->assertNotNull($result->getById('60'));
        $this->assertNotNull($result->getById('61'));
        $this->assertNotNull($result->getById('63'));
    }
}
