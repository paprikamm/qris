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
     * @var PayloadRoot
     */
    private $payloadRoot;

    public function __construct(PayloadRoot $payloadRoot)
    {
        $this->valid = false;
        $this->payloadRoot = $payloadRoot;
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
     * @return string|null
     */
    public function getErrorMessage(): ?string
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
     * @return PayloadRoot
     */
    public function getPayloadRoot(): PayloadRoot
    {
        return $this->payloadRoot;
    }

    /**
     * @param PayloadRoot $payloadRoot
     * @return PayloadRoot
     */
    public function setPayloadRoot(PayloadRoot $payloadRoot): self
    {
        $this->payloadRoot = $payloadRoot;

        return $this;
    }

    /**
     * @return Payload[]
     */
    public function getPayloads(): array
    {
        return $this->payloadRoot->getPayloads();
    }

    /**
     * @param Payload[] $payload
     * @return FactoryResult
     */
    public function addPayload(Payload $payload): self
    {
        $this->payloadRoot->addPayload($payload);

        return $this;
    }

    /**
     * @param string $id
     * @return Payload|null
     */
    public function getById(string $id): ?Payload
    {
        return $this->payloadRoot->getById($id);
    }
}
