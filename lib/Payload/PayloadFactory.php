<?php

namespace Paprika\Payload;

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
        $result = new FactoryResult();
        $result->setValid(false);

        $index = 0;
        while ($index < strlen($code)) {
            $id = substr($code, $index, 2);
            $index += 2;

            $length = (int) substr($code, $index, 2);
            $index += 2;

            $value = substr($code, $index, $length);
            $index += $length;

            $objectSchema = $schema->getById($id);
            if (!$objectSchema) {
                return $result;
            }

            $payload = $objectSchema->createPayload($value);
            if (!$payload) {
                return $result;
            }

            $result->addPayload($payload);
        }

        // @TODO: check mandatories
        // @TODO: check 00 first and CRC last

        $result->setValid(true);

        return $result;
    }
}
