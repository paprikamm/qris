<?php

namespace Paprika\Payload;

class FactoryResult
{
    /**
     * @var bool
     */
    private $valid;

    /**
     * @var Payload[]
     */
    private $payloads;

    public function __construct()
    {
        $this->valid = false;
        $this->payloads = [];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return FactoryResult
     */
    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

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
     * @param Payload[] $payload
     * @return FactoryResult
     */
    public function addPayload(Payload $payload): self
    {
        $this->payloads[] = $payload;

        return $this;
    }
}
