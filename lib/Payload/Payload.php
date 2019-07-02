<?php

namespace Paprika\Payload;

use Paprika\Schema\DataObjectSchema;

class Payload
{
    /**
     * @var DataObjectSchema
     */
    private $schema;

    /**
     * @var string
     */
    private $value;

    /**
     * @var Payload[]
     */
    private $children;

    public function __construct()
    {
        $this->children = [];
    }

    /**
     * @return DataObjectSchema
     */
    public function getSchema(): DataObjectSchema
    {
        return $this->schema;
    }

    /**
     * @param DataObjectSchema $schema
     * @return Payload
     */
    public function setSchema(DataObjectSchema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Payload
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Payload[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param Payload[] $child
     * @return Payload
     */
    public function addChild(Payload $payload): self
    {
        $this->children[] = $payload;

        return $this;
    }
}
