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
     * @var callback
     */
    private $childrenCallback;

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
     * @return bool
     */
    public function isIdRange(): bool
    {
        return strpos($this->getId(), '-') !== false;
    }

    /**
     * @return string[]
     */
    public function getIdRange(): array
    {
        $id = $this->getId();

        if ($this->isIdRange()) {
            $ids = explode('-', $id);

            return range($ids[0], $ids[1]);
        }

        return [$id, $id];
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
     * @param string $id
     * @return DataObjectSchema|null
     */
    public function getChildById(string $id): ?DataObjectSchema
    {
        foreach ($this->getChildren() as $child) {
            if ($child->isIdRange()) {
                if (in_array($id, $child->getIdRange())) {
                    return $child;
                }
            } else if ($child->getId() == $id) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @return callable|null
     */
    public function getCallback(): ?callable
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
     * @return callable|null
     */
    public function getChildrenCallback(): ?callable
    {
        return $this->childrenCallback;
    }

    /**
     * ChildrenCallback will be passed DataObjectSchema and Payload as parameters and must return boolean.
     * ChildrenCallback will be used to show whether children need to be processed or not.
     * @TODO refactor so that we dont need children callback
     *
     * @param callable $childrenCallback
     * @return DataObjectSchema
     */
    public function setChildrenCallback(callable $childrenCallback = null): self
    {
        $this->childrenCallback = $childrenCallback;

        return $this;
    }

    /**
     * @param Schema $schema
     * @param string $id
     * @param int $length
     * @param string $value
     * @return Payload|null
     */
    public function createPayload(Schema $schema, string $id, int $length, string $value): ?Payload
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
        $payload->setId($id);
        $payload->setLength($length);
        $payload->setSchema($this);
        $payload->setValue($value);

        $valid = $this->createChildrenPayload($schema, $payload, $value);
        if (!$valid) {
            return null;
        }

        $callback = $this->getCallback();
        if ($callback) {
            $valid = $callback($schema, $payload);
            if (!$valid) {
                return null;
            }
        }

        return $payload;
    }

    /**
     * @param Schema $schema
     * @param Payload $payload
     * @param string $code
     * @return bool
     */
    private function createChildrenPayload(Schema $schema, Payload $payload, string $code): bool
    {
        if (count($this->getChildren()) <= 0) {
            return true;
        }
        $childrenCallback = $this->getChildrenCallback();
        if ($childrenCallback) {
            $needProcess = $childrenCallback($this, $payload);
            if (!$needProcess) {
                return true;
            }
        }

        $codeLength = strlen($code);
        $index = 0;
        while ($index < $codeLength) {
            $id = substr($code, $index, 2);
            $index += 2;

            $length = (int) substr($code, $index, 2);
            $index += 2;

            $value = substr($code, $index, $length);
            $index += $length;

            $objectSchema = $this->getChildById($id);
            if (!$objectSchema) {
                return false;
            }

            $childPayload = $objectSchema->createPayload($schema, $id, $length, $value);
            if (!$childPayload) {
                return false;
            }

            $payload->addChild($childPayload);
        }

        return true;
    }
}
