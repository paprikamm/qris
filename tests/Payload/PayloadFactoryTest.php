<?php

namespace Tests\Schema;

use Paprika\Payload\PayloadFactory;
use Paprika\Schema\SchemaFactory;
use PHPUnit\Framework\TestCase;

class PayloadFactoryTest extends TestCase
{
    public function testCodeNotBeginWith00()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '01021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertNotNull($result->getErrorMessage());
    }

    public function testCodeNotEndsWithCRC()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA610510310');

        $this->assertFalse($result->isValid());
        $this->assertNotNull($result->getErrorMessage());
    }

    public function testWrongFormat()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        // set wrong length for ID 02 (15 -> 13)
        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102134076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertNotNull($result->getErrorMessage());
    }

    public function testMandatoriesNotIncluded()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI53033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertNotNull($result->getErrorMessage());
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
        $this->assertNull($result->getErrorMessage());
        $this->assertNotNull($result->getById('00'));
        $this->assertNotNull($result->getById('01'));
        $this->assertNotNull($result->getById('02'));
        $this->assertNotNull($result->getById('04'));

        $id26 = $result->getById('26');
        $this->assertNotNull($id26);
        $this->assertNotNull($id26->getChildById('01'));
        $this->assertNotNull($id26->getChildById('02'));
        $this->assertNotNull($id26->getChildById('03'));

        $id27 = $result->getById('27');
        $this->assertNotNull($id27);
        $this->assertNotNull($id27->getChildById('00'));
        $this->assertNotNull($id27->getChildById('01'));
        $this->assertNotNull($id27->getChildById('02'));

        $id51 = $result->getById('51');
        $this->assertNotNull($id51);
        $this->assertNotNull($id51->getChildById('02'));
        $this->assertNotNull($id51->getChildById('03'));

        $this->assertNotNull($result->getById('52'));
        $this->assertNotNull($result->getById('53'));
        $this->assertNotNull($result->getById('58'));
        $this->assertNotNull($result->getById('59'));
        $this->assertNotNull($result->getById('60'));
        $this->assertNotNull($result->getById('61'));
        $this->assertNotNull($result->getById('63'));
    }
}
