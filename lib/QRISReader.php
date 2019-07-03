<?php

namespace Paprika;

use Paprika\Payload\Payload;
use Paprika\Payload\PayloadFactory;
use Paprika\Payload\PayloadRoot;
use Paprika\Schema\SchemaFactory;

class QRISReader
{
    /**
     * @var SchemaFactory
     */
    private $schemaFactory;

    /**
     * @var PayloadFactory
     */
    private $payloadFactory;

    public function __construct(SchemaFactory $schemaFactory, PayloadFactory $payloadFactory)
    {
        $this->schemaFactory = $schemaFactory;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * Membaca $code dan mengubahnya menjadi object QRIS
     *
     * @param string $code
     * @return QRIS
     */
    public function read($code)
    {
        $schema = $this->schemaFactory->createV01();
        $result = $this->payloadFactory->create($schema, $code);
        if (!$result->isValid()) {
            return null;
        }

        $qris = $this->convertPayloadsToQRIS($result->getPayloadRoot());

        return $qris;
    }

    /**
     * @param PayloadRoot $payloadRoot
     * @return QRIS
     */
    private function convertPayloadsToQRIS(PayloadRoot $payloadRoot): QRIS
    {
        $qris = new QRIS();
        $qris->payloadRoot = $payloadRoot;

        $methodPayload = $payloadRoot->getById('01');
        if ($methodPayload->getValue() == '11') {
            $qris->method = QRIS::METHOD_STATIC;
        } else if ($methodPayload->getValue() == '12') {
            $qris->method = QRIS::METHOD_DYNAMIC;
        }

        $merchantPayloads = $payloadRoot->getByIdRange('02-51,80-84');
        foreach ($merchantPayloads as $merchantPayload) {
            $schemaId = $merchantPayload->getId();
            $merchantAccount = new MerchantAccountInformation();
            $merchantAccount->id = $schemaId;

            if ($schemaId >= 2 && $schemaId <= 3) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_VISA;
            } else if ($schemaId >= 4 && $schemaId <= 5) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_MASTERCARD;
            } else if (($schemaId >= 6 && $schemaId <= 8) || ($schemaId >= 17 && $schemaId <= 25)) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_EMVCO;
            } else if ($schemaId >= 9 && $schemaId <= 10) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_DISCOVER;
            } else if ($schemaId >= 11 && $schemaId <= 12) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_AMEX;
            } else if ($schemaId >= 13 && $schemaId <= 14) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_JCB;
            } else if ($schemaId >= 15 && $schemaId <= 16) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_UNION_PAY;
            } else if ($schemaId >= 26 && $schemaId <= 50) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_DOMESTIC;
            } else if ($schemaId == 51) {
                $merchantAccount->type = MerchantAccountInformation::TYPE_CENTRAL_REPOSITORY;
            }

            $uniqueIDPayload = $merchantPayload->getChildById('00');
            if ($uniqueIDPayload) {
                $merchantAccount->uniqueID = $uniqueIDPayload->getValue();
            }

            $PANPayload = $merchantPayload->getChildById('01');
            if ($PANPayload) {
                $merchantAccount->merchantPAN = $PANPayload->getValue();
            }

            $merchantIDPayload = $merchantPayload->getChildById('02');
            if ($merchantIDPayload) {
                $merchantAccount->merchantID = $merchantIDPayload->getValue();
            }

            $merchantCriteriaPayload = $merchantPayload->getChildById('03');
            if ($merchantCriteriaPayload) {
                $merchantAccount->merchantCriteria = $merchantCriteriaPayload->getValue();
            }

            $qris->merchantAccounts[] = $merchantAccount;
        }

        $mccPayload = $payloadRoot->getById('52');
        $qris->mcc = $mccPayload->getValue();

        $currencyPayload = $payloadRoot->getById('53');
        $qris->currency = $currencyPayload->getValue();

        $amountPayload = $payloadRoot->getById('54');
        if ($amountPayload) {
            $qris->amount = $amountPayload->getValue();
        }

        $conveniencePayload = $payloadRoot->getById('55');
        if ($conveniencePayload) {
            if ($conveniencePayload->getValue() == '01') {
                $qris->convenienceType = QRIS::CONVENIENCE_TYPE_BOTH;
            } else if ($conveniencePayload->getValue() == '02') {
                $qris->convenienceType = QRIS::CONVENIENCE_TYPE_FIXED;

                $fixedPayload = $payloadRoot->getById('56');
                $qris->convenienceAmount = $fixedPayload->getValue();
            } else if ($conveniencePayload->getValue() == '03') {
                $qris->convenienceType = QRIS::CONVENIENCE_TYPE_PERCENTAGE;

                $percentagePayload = $payloadRoot->getById('57');
                $qris->conveniencePercentage = $percentagePayload->getValue();
            }
        } else {
            $qris->convenienceType = QRIS::CONVENIENCE_TYPE_NONE;
        }

        $countryPayload = $payloadRoot->getById('58');
        $qris->countryCode = $countryPayload->getValue();

        $merchantNamePayload = $payloadRoot->getById('59');
        $qris->merchantName = $merchantNamePayload->getValue();

        $merchantCityPayload = $payloadRoot->getById('60');
        $qris->merchantCity = $merchantCityPayload->getValue();

        $postalCodePayload = $payloadRoot->getById('61');
        if ($postalCodePayload) {
            $qris->postalCode = $postalCodePayload->getValue();
        }

        $additionalFieldsPayload = $payloadRoot->getById('62');
        if ($additionalFieldsPayload) {
            foreach ($additionalFieldsPayload->getChildren() as $child) {
                $field = new AdditionalField();
                $field->name = $child->getSchema()->getName();
                $field->id = $child->getId();
                $field->value = $child->getValue();

                $qris->additionalFields[] = $field;
            }
        }

        return $qris;
    }
}
