<?php

namespace Tests;

use Paprika\Payload\PayloadFactory;
use Paprika\QRIS;
use Paprika\QRISReader;
use Paprika\Schema\SchemaFactory;
use PHPUnit\Framework\TestCase;

class QRISReaderTest extends TestCase
{
    public function testReadDynamicSuccess()
    {
        $schemaFactory = new SchemaFactory();
        $payloadFactory = new PayloadFactory();

        $reader = new QRISReader($schemaFactory, $payloadFactory);
        $qris = $reader->read('00020101021202154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605409100000.00550202560410005802ID5909BASO JONO6007JAKARTA6105103106304C007');

        $this->assertEquals(QRIS::METHOD_DYNAMIC, $qris->method);
        $this->assertTrue(count($qris->merchantAccounts) > 0);
        $this->assertEquals('5812', $qris->mcc);
        $this->assertEquals('360', $qris->currency);
        $this->assertEquals('100000.00', $qris->amount);
        $this->assertEquals(QRIS::CONVENIENCE_TYPE_FIXED, $qris->convenienceType);
        $this->assertEquals('1000', $qris->convenienceAmount);
        $this->assertEquals('0', $qris->conveniencePercentage);
        $this->assertEquals('ID', $qris->countryCode);
        $this->assertEquals('BASO JONO', $qris->merchantName);
        $this->assertEquals('JAKARTA', $qris->merchantCity);
        $this->assertEquals('10310', $qris->postalCode);
    }
}
