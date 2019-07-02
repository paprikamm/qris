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
    private $id;

    /**
     * @var int
     */
    private $length;

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
        $this->length = 0;
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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Payload
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return Payload
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

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

    /**
     * @param string $id
     * @return Payload|null
     */
    public function getChildById(string $id): ?Payload
    {
        foreach ($this->getChildren() as $child) {
            if ($child->getId() == $id) {
                return $child;
            }
        }

        return null;
    }
}
