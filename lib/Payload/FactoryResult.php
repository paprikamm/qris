<?php

namespace Paprika\Payload;

class FactoryResult
{
    /**
     * @var bool
     */
    private $valid;

    /**
     * @var string
     */
    private $errorMessage;

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
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return FactoryResult
     */
    public function setErrorMessage(string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

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
