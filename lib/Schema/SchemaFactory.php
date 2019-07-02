<?php

namespace Paprika\Schema;

class SchemaFactory
{
    public function createV01(): Schema
    {
        $schema = new Schema();

        $schema->addDataObject($this->createDataObject(
            'Payload Format Indicator',
            '00',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            2,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Point of Initiation Method',
            '01',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            2,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createMerchantAccountInformation());

        $schema->addDataObject($this->createDataObject(
            'Merchant Account Information Reserved untuk domestik ID',
            '46-50',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createMerchantAccountInformation51());

        $schema->addDataObject($this->createDataObject(
            'Merchant Category Code',
            '52',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            4,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Transaction Currency',
            '53',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            3,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Transaction Amount',
            '54',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            13,
            DataObjectSchema::REQUIREMENT_CONDITIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Tip or Convenience Indicator',
            '55',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            2,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Value of Convenience Fee Fixed',
            '56',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            13,
            DataObjectSchema::REQUIREMENT_CONDITIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Value of Convenience Fee Percentage',
            '57',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            5,
            DataObjectSchema::REQUIREMENT_CONDITIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Country Code',
            '58',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            2,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Merchant Name',
            '59',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Merchant City',
            '60',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            15,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $schema->addDataObject($this->createDataObject(
            'Postal Code',
            '61',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            10,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createAdditionalDataObject());
        $schema->addDataObject($this->createLanguageTemplate());

        $schema->addDataObject($this->createDataObject(
            'RFU for EMVCo',
            '65-79',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Additional Allocation for Merchant Account Information',
            '80-84',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'Unreserved Templates',
            '85-99',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $schema->addDataObject($this->createDataObject(
            'CRC',
            '63',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            4,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        return $schema;
    }

    /**
     * @return DataObjectSchema
     */
    private function createAdditionalDataObject(): DataObjectSchema
    {
        $dataObject = $this->createDataObject(
            'Additional Data Field Template',
            '62',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        );

        $dataObject->addChild($this->createDataObject(
            'Bill Number',
            '01',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Mobile Number',
            '02',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Store Label',
            '03',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Loyalty Number',
            '04',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Reference Label',
            '05',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Customer Label',
            '06',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Terminal Label',
            '07',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Purpose of Transaction',
            '08',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Purpose of Transaction',
            '09',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            3,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'RFU for EMVCO',
            '10-49',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_UNSPECIFIED,
            0,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Payment System specific templates.',
            '50-98',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_UNSPECIFIED,
            0,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Proprietary data',
            '99',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            64,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        return $dataObject;
    }

    /**
     * @return DataObjectSchema
     */
    private function createMerchantAccountInformation(): DataObjectSchema
    {
        $dataObject = $this->createDataObject(
            'Merchant Account Information',
            '02-45',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_MANDATORY
        );

        $dataObject->addChild($this->createDataObject(
            'Globally Unique Identifier',
            '00',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            32,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant PAN',
            '01',
            DataObjectSchema::FORMAT_NUMERIC,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            19,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant ID',
            '02',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            15,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant Criteria',
            '03',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            3,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        return $dataObject;
    }

    /**
     * @return DataObjectSchema
     */
    private function createMerchantAccountInformation51(): DataObjectSchema
    {
        $dataObject = $this->createDataObject(
            'Merchant Account Information Domestic Central Repository',
            '51',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_CONDITIONAL
        );

        $dataObject->addChild($this->createDataObject(
            'Globally Unique Identifier',
            '00',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            32,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant ID',
            '02',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            15,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant Criteria',
            '03',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            3,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        return $dataObject;
    }

    /**
     * @return DataObjectSchema
     */
    private function createLanguageTemplate(): DataObjectSchema
    {
        $dataObject = $this->createDataObject(
            'Merchant Information — Language Template',
            '64',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        );

        $dataObject->addChild($this->createDataObject(
            'Language Preference',
            '00',
            DataObjectSchema::FORMAT_ALPHA,
            DataObjectSchema::LENGTH_TYPE_FIXED,
            2,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant Name— Alternate Language',
            '01',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            25,
            DataObjectSchema::REQUIREMENT_MANDATORY
        ));

        $dataObject->addChild($this->createDataObject(
            'Merchant City— Alternate Language',
            '02',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            15,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        $dataObject->addChild($this->createDataObject(
            'RFU for EMVCo',
            '03-99',
            DataObjectSchema::FORMAT_STRING,
            DataObjectSchema::LENGTH_TYPE_MAXIMUM,
            99,
            DataObjectSchema::REQUIREMENT_OPTIONAL
        ));

        return $dataObject;
    }

    /**
     * @param $name
     * @param $id
     * @param $format
     * @param $lengthType
     * @param $length
     * @param $requirement
     * @param callable|null $callback
     * @return DataObjectSchema
     */
    private function createDataObject($name, $id, $format, $lengthType, $length, $requirement, $callback = null): DataObjectSchema
    {
        $dataObject = new DataObjectSchema();
        $dataObject->setName($name);
        $dataObject->setId($id);
        $dataObject->setFormat($format);
        $dataObject->setLengthType($lengthType);
        $dataObject->setLength($length);
        $dataObject->setRequirement($requirement);
        $dataObject->setCallback($callback);

        return $dataObject;
    }
}
