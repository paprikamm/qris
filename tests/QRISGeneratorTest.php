<?php

namespace Tests;

use Paprika\MerchantAccountInformation;
use Paprika\QRIS;
use Paprika\QRISGenerator;
use PHPUnit\Framework\TestCase;

class QRISGeneratorTest extends TestCase
{
    public function testGenerateDynamicSuccess()
    {
        $qris = new QRIS();
        $qris->method = QRIS::METHOD_DYNAMIC;
        $qris->mcc = '5812';
        $qris->currency = '360';
        $qris->amount = '100000.00';
        $qris->convenienceType = QRIS::CONVENIENCE_TYPE_FIXED;
        $qris->convenienceAmount = '1000';
        $qris->countryCode = 'ID';
        $qris->merchantName = 'BASO JONO';
        $qris->merchantCity = 'JAKARTA';
        $qris->postalCode = '10310';

        $merchantAccount1 = new MerchantAccountInformation();
        $merchantAccount1->id = '02';
        $merchantAccount1->type = MerchantAccountInformation::TYPE_VISA;
        $merchantAccount1->uniqueID = '407662009999999';
        $qris->merchantAccounts[] = $merchantAccount1;

        $merchantAccount2 = new MerchantAccountInformation();
        $merchantAccount2->id = '04';
        $merchantAccount2->type = MerchantAccountInformation::TYPE_MASTERCARD;
        $merchantAccount2->uniqueID = '508999888888888';
        $qris->merchantAccounts[] = $merchantAccount2;

        $merchantAccount3 = new MerchantAccountInformation();
        $merchantAccount3->id = '26';
        $merchantAccount3->type = MerchantAccountInformation::TYPE_DOMESTIC;
        $merchantAccount3->merchantPAN = '936000090123456789';
        $merchantAccount3->merchantID = 'ABCD12345678901';
        $merchantAccount3->merchantCriteria = 'UMI';
        $qris->merchantAccounts[] = $merchantAccount3;

        $merchantAccount4 = new MerchantAccountInformation();
        $merchantAccount4->id = '27';
        $merchantAccount4->type = MerchantAccountInformation::TYPE_DOMESTIC;
        $merchantAccount4->uniqueID = 'ID.CO.PERMATABANK.WWW';
        $merchantAccount4->merchantPAN = '936000132987654321';
        $merchantAccount4->merchantID = 'BDFHF1234567890';
        $qris->merchantAccounts[] = $merchantAccount4;

        $merchantAccount5 = new MerchantAccountInformation();
        $merchantAccount5->id = '51';
        $merchantAccount5->type = MerchantAccountInformation::TYPE_CENTRAL_REPOSITORY;
        $merchantAccount5->merchantID = 'ABCDE1234567890';
        $merchantAccount5->merchantCriteria = 'UMI';
        $qris->merchantAccounts[] = $merchantAccount5;

        $generator = new QRISGenerator();
        $qrCode = $generator->generate($qris);

        $this->assertEquals('00020101021202154076620099999990415508999888888888264801189360000901234567890215ABCD123456789010303UMI27660021ID.CO.PERMATABANK.WWW01189360001329876543210215BDFHF123456789051260215ABCDE12345678900303UMI5204581253033605409100000.00550202560410005802ID5909BASO JONO6007JAKARTA6105103106304C007', $qrCode->getText());
    }
}
