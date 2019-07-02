<?php

namespace Paprika\Schema;

class Schema
{
    /**
     * @var DataObjectSchema[]
     */
    private $dataObjects;

    /**
     * @param DataObjectSchema $dataObject
     * @return Schema
     */
    public function addDataObject(DataObjectSchema $dataObject): self
    {
        $this->dataObjects[] = $dataObject;

        return $this;
    }

    /**
     * @return DataObjectSchema[]
     */
    public function getDataObjects(): array
    {
        return $this->dataObjects;
    }

    /**
     * @param string $id
     * @return DataObjectSchema
     */
    public function getById($id): ?DataObjectSchema
    {
        foreach ($this->getDataObjects() as $dataObjectSchema) {
            $schemaId = $dataObjectSchema->getId();
            if (strpos($schemaId, '-') !== false) {
                $ids = explode('-', $schemaId);
                if ($id >= $ids[0] && $id <= $ids[1]) {
                    return $dataObjectSchema;
                }
            } else if ($schemaId == $id) {
                return $dataObjectSchema;
            }
        }

        return null;
    }
}
