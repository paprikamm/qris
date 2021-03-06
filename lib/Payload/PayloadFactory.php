<?php

namespace Paprika\Payload;

use Paprika\Encryption;
use Paprika\Schema\Schema;

class PayloadFactory
{
    /**
     * Parse $code according to $schema and convert it to $payloads
     *
     * @param Schema $schema
     * @param string $code
     * @return FactoryResult
     */
    public function create(Schema $schema, $code): FactoryResult
    {
        $payloadRoot = new PayloadRoot();
        $payloadRoot->setSchema($schema);

        $result = new FactoryResult($payloadRoot);

        $codeLength = strlen($code);
        $index = 0;
        while ($index < $codeLength) {
            $id = substr($code, $index, 2);
            $index += 2;

            $length = (int) substr($code, $index, 2);
            $index += 2;

            $value = substr($code, $index, $length);
            $index += $length;

            $objectSchema = $schema->getById($id);
            if (!$objectSchema) {
                $result->setErrorMessage(sprintf('ID %s not found (index %s)', $id, $index));
                return $result;
            }

            $payload = $objectSchema->createPayload($payloadRoot, $id, $length, $value);
            if (!$payload) {
                $result->setErrorMessage(sprintf('Value %s not valid (index %s)', $value, $index));
                return $result;
            }

            $result->addPayload($payload);
        }

        $payloads = $result->getPayloads();

        // check mandatories
        $isMandatoriesValid = $this->validateMandatories($schema, $payloads);
        if (!$isMandatoriesValid) {
            $result->setErrorMessage('Mandatory fields are missing');

            return $result;
        }

        // Check 00
        $formatIndicatorPayload = $payloads[0];
        if ($formatIndicatorPayload->getSchema()->getId() != '00') {
            $result->setErrorMessage('QR must begin with 00');

            return $result;
        } else if ($formatIndicatorPayload->getValue() != '01') {
            $result->setErrorMessage('Format indicator must have value 01');

            return $result;
        }

        // check CRC
        $count = count($payloads);
        $crcPayload = $payloads[$count - 1];
        $checksum = Encryption::checksum(substr($code, 0, $codeLength - 4));
        if ($crcPayload->getSchema()->getId() != '63') {
            $result->setErrorMessage('QR must end with CRC');

            return $result;
        } else if ($crcPayload->getValue() !== $checksum) {
            $result->setErrorMessage('Checksum value invalid');

            return $result;
        }

        $result->setValid(true);

        return $result;
    }

    /**
     * @param Schema $schema
     * @param Payload[] $payloads
     * @return bool
     */
    private function validateMandatories(Schema $schema, array $payloads = []): bool
    {
        $mandatories = $schema->getMandatoryDataObjects();
        $ids = [];
        foreach ($payloads as $payload) {
            $ids[] = $payload->getId();
        }
        foreach ($mandatories as $mandatory) {
            if ($mandatory->isIdRange()) {
                $range = $mandatory->getIdRange();

                // check $ids exists in range
                $exists = count(array_intersect($ids, $range)) > 0;
                if (!$exists) {
                    return false;
                }
            } else if (!in_array($mandatory->getId(), $ids)) {
                return false;
            }
        }

        return true;
    }
}
