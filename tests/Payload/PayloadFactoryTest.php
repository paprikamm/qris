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
        $result = $factory->create($schema, '01021100020102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertEquals('QR must begin with 00', $result->getErrorMessage());
    }

    public function testCodeNotEndsWithCRC()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '0002016304BC3901021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA610510310');

        $this->assertFalse($result->isValid());
        $this->assertEquals('QR must end with CRC', $result->getErrorMessage());
    }

    public function testWrongFormat()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        // set wrong length for ID 02 (15 -> 13)
        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102134076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertEquals('Value 9876543210215BDFHF12345678905126 not valid (index 176)', $result->getErrorMessage());
    }

    public function testMandatoriesNotIncluded()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI53033605802ID5909BASO JONO6007JAKARTA6105103106304BC39');

        $this->assertFalse($result->isValid());
        $this->assertEquals('Mandatory fields are missing', $result->getErrorMessage());
    }

    public function testWrongFormatAmount()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021102154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605409100000,005802ID5909BASO JONO6007JAKARTA6105103106304E7DF');

        $this->assertFalse($result->isValid());
        $this->assertEquals('Value 100000,00 not valid (index 230)', $result->getErrorMessage());
    }

    public function testSuccessWithoutAmount()
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

    public function testSuccessWithAmount()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021202154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605409100000.005802ID5909BASO JONO6007JAKARTA610510310630428F4');

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getErrorMessage());
        $this->assertNotNull($result->getById('54'));
        $this->assertEquals('100000.00', $result->getById('54')->getValue());
    }

    public function testConvenienceSuccess()
    {
        $schemaFactory = new SchemaFactory();
        $schema = $schemaFactory->createV01();

        $factory = new PayloadFactory();
        $result = $factory->create($schema, '00020101021202154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605409100000.00550202560410005802ID5909BASO JONO6007JAKARTA6105103106304C007');

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getErrorMessage());
        $this->assertNotNull($result->getById('55'));
        $this->assertEquals('02', $result->getById('55')->getValue());
        $this->assertNotNull($result->getById('56'));
        $this->assertEquals('1000', $result->getById('56')->getValue());
    }
}
