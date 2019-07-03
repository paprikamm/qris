<?php

namespace Paprika\Payload;

use Paprika\Schema\Schema;

class PayloadRoot
{
    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var Payload[]
     */
    private $payloads;

    public function __construct()
    {
        $this->payloads = [];
    }

    /**
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * @param Schema $schema
     * @return PayloadRoot
     */
    public function setSchema(Schema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return Payload[]
     */
    public function getPayloads(): array
    {
        return $this->payloads;
    }

    /**
     * @param Payload $payload
     * @return PayloadRoot
     */
    public function addPayload(Payload $payload): self
    {
        $this->payloads[] = $payload;

        return $this;
    }

    /**
     * @param string $id
     * @return Payload|null
     */
    public function getById(string $id): ?Payload
    {
        foreach ($this->getPayloads() as $payload) {
            if ($payload->getId() == $id) {
                return $payload;
            }
        }

        return null;
    }
}
