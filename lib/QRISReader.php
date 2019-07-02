<?php

namespace Paprika;

use Paprika\Payload\Payload;
use Paprika\Payload\PayloadFactory;
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

        $payloads = $result->getPayloads();
        $qris = $this->convertPayloadsToQRIS($payloads);

        return $qris;
    }

    /**
     * @param Payload[] $payloads
     * @return QRIS
     */
    private function convertPayloadsToQRIS(array $payloads): QRIS
    {
        $qris = new QRIS();

        foreach ($payloads as $payload) {
            if ($payload->getSchema()->getId() === '00') {

            }
        }

        return $qris;
    }
}
