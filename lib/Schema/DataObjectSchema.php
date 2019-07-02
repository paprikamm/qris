<?php

namespace Paprika\Schema;

use Paprika\Payload\Payload;

class DataObjectSchema
{
    const FORMAT_NUMERIC = 'N';
    const FORMAT_STRING = 'S';
    const FORMAT_ALPHA = 'ans';

    const LENGTH_TYPE_FIXED = 'fixed';
    const LENGTH_TYPE_MAXIMUM = 'maximum';
    const LENGTH_TYPE_UNSPECIFIED = 'unspecified';

    const REQUIREMENT_MANDATORY = 'M';
    const REQUIREMENT_CONDITIONAL = 'C';
    const REQUIREMENT_OPTIONAL = 'O';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $format;

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
    private $lengthType;

    /**
     * @var string
     */
    private $requirement;

    /**
     * @var callback
     */
    private $callback;

    /**
     * @var DataObjectSchema[]
     */
    private $children;

    public function __construct()
    {
        $this->children = [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DataObjectSchema
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return DataObjectSchema
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

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
     * @return DataObjectSchema
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLengthType(): string
    {
        return $this->lengthType;
    }

    /**
     * @param string $lengthType
     * @return DataObjectSchema
     */
    public function setLengthType(string $lengthType): self
    {
        $this->lengthType = $lengthType;

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
     * @return DataObjectSchema
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequirement(): string
    {
        return $this->requirement;
    }

    /**
     * @param string $requirement
     * @return DataObjectSchema
     */
    public function setRequirement(string $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * @param DataObjectSchema $dataObjectSchema
     * @return DataObjectSchema
     */
    public function addChild(DataObjectSchema $dataObjectSchema): self
    {
        $this->children[] = $dataObjectSchema;

        return $this;
    }

    /**
     * @return DataObjectSchema[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * Callback will be passed Schema and Payload as parameters and must return boolean as validation result
     *
     * @param callable $callback
     * @return DataObjectSchema
     */
    public function setCallback(callable $callback = null): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @param string $value
     * @return Payload|null
     */
    public function createPayload(string $value): Payload
    {
        $len = strlen($value);
        if ($this->getLengthType() === self::LENGTH_TYPE_FIXED && $len != $this->getLength()) {
            return null;
        } else if ($this->getLengthType() === self::LENGTH_TYPE_MAXIMUM && $len > $this->getLength()) {
            return null;
        }

        if ($this->getFormat() === self::FORMAT_NUMERIC && !is_numeric($value)) {
            return null;
        } else if ($this->getFormat() === self::FORMAT_STRING && !is_string($value)) {
            return null;
        }

        $payload = new Payload();
        $payload->setSchema($this);
        $payload->setValue($value);

        return $payload;
    }
}
