<?php

namespace Paprika;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\QrCodeInterface;

class QRISGenerator
{
    /**
     * @param QRIS $qris
     * @return QrCodeInterface
     */
    public function generate(QRIS $qris): QrCodeInterface
    {
        $string = $this->convertToString($qris);
        $qrCode = new QrCode($string);

        return $qrCode;
    }

    /**
     * @param QRIS $qris
     * @return string
     */
    private function convertToString(QRIS $qris): string
    {
        $string = '000201';
        if ($qris->method == QRIS::METHOD_STATIC) {
            $string .= '010211';
        } else if ($qris->method == QRIS::METHOD_DYNAMIC) {
            $string .= '010212';
        }

        foreach ($qris->merchantAccounts as $merchantAccount) {
            $schemaID = $merchantAccount->id;
            $uniqueID = $merchantAccount->uniqueID;
            $merchantPAN = $merchantAccount->merchantPAN;
            $merchantID = $merchantAccount->merchantID;
            $merchantCriteria = $merchantAccount->merchantCriteria;

            $len = strlen($uniqueID);

            if ($schemaID >= 2 && $schemaID <= 25) {
                $string .= sprintf('%s%s%s', $schemaID, $len, $uniqueID);
            } else {
                $childrenString = '';
                if ($uniqueID) {
                    $childrenString .= sprintf('00%s%s', $this->len($uniqueID), $uniqueID);
                }
                if ($merchantPAN) {
                    $childrenString .= sprintf('01%s%s', $this->len($merchantPAN), $merchantPAN);
                }
                if ($merchantID) {
                    $childrenString .= sprintf('02%s%s', $this->len($merchantID), $merchantID);
                }
                if ($merchantCriteria) {
                    $childrenString .= sprintf('03%s%s', $this->len($merchantCriteria), $merchantCriteria);
                }

                $string .= sprintf('%s%s%s', $schemaID, $this->len($childrenString), $childrenString);
            }
        }

        $mcc = $qris->mcc;
        $string .= sprintf('52%s%s', $this->len($mcc), $mcc);

        $currency = $qris->currency;
        $string .= sprintf('53%s%s', $this->len($currency), $currency);

        $amount = $qris->amount;
        if ($amount > 0) {
            $string .= sprintf('54%s%s', $this->len($amount), $amount);
        }

        $convenienceType = $qris->convenienceType;
        if ($convenienceType !== QRIS::CONVENIENCE_TYPE_NONE) {
            $typeCode = '';
            if ($convenienceType === QRIS::CONVENIENCE_TYPE_BOTH) {
                $typeCode = '01';
            } else if ($convenienceType === QRIS::CONVENIENCE_TYPE_FIXED) {
                $typeCode = '02';
            } else if ($convenienceType === QRIS::CONVENIENCE_TYPE_PERCENTAGE) {
                $typeCode = '03';
            }

            $string .= sprintf('55%s%s', $this->len($typeCode), $typeCode);
        }

        $convenienceAmount = $qris->convenienceAmount;
        if ($convenienceAmount > 0) {
            $string .= sprintf('56%s%s', $this->len($convenienceAmount), $convenienceAmount);
        }

        $conveniencePercentage = $qris->conveniencePercentage;
        if ($conveniencePercentage > 0) {
            $string .= sprintf('57%s%s', $this->len($conveniencePercentage), $conveniencePercentage);
        }

        $countryCode = $qris->countryCode;
        $string .= sprintf('58%s%s', $this->len($countryCode), $countryCode);

        $merchantName = $qris->merchantName;
        $string .= sprintf('59%s%s', $this->len($merchantName), $merchantName);

        $merchantCity = $qris->merchantCity;
        $string .= sprintf('60%s%s', $this->len($merchantCity), $merchantCity);

        $postalCode = $qris->postalCode;
        if ($postalCode) {
            $string .= sprintf('61%s%s', $this->len($postalCode), $postalCode);
        }

        $fields = $qris->additionalFields;
        if (count($fields) > 0) {
            $fieldsString = '';
            foreach ($fields as $field) {
                $id = $field->id;
                $value = $field->value;
                $fieldsString .= sprintf('%s%s%s', $id, $this->len($value), $value);
            }

            $string .= sprintf('62%s%s', $this->len($fieldsString), $fieldsString);
        }

        $string .= '6304';
        $crc = Encryption::checksum($string);
        $string .= $crc;

        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    private function len(string $string): string
    {
        return str_pad(strlen($string), 2, '0', STR_PAD_LEFT);
    }
}
