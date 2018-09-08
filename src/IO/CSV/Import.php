<?php

namespace DataFlow\IO\CSV;

use DataFlow\Data\Entity;
use DataFlow\Data\EntityCollection;

class Import
{
    /**
     * @param string $filename
     * @param ImportPolicy $importPolicy
     * @return EntityCollection
     */
    public static function fromFile($filename, ImportPolicy $importPolicy)
    {
        $entities = [];
        $file = fopen($filename, 'r');

        $maxIndex = max(array_keys($importPolicy->getColumnDefinitions()));

        $rowIndex = 0;
        while (($rowArray = fgetcsv(
                $file,
                0,
                $importPolicy->getDelimiter(),
                $importPolicy->getEnclosure(),
                $importPolicy->getEscape()
            )) !== false) {
            if ($rowIndex++ < $importPolicy->getHeaderOffset()) {
                continue;
            }

            if (empty($rowArray) || (count($rowArray) === 1 && $rowArray[0] === null)) {
                continue;
            }

            if (count($rowArray) - 1 < $maxIndex) {
                if ($importPolicy->isQuiet()) {
                    continue;
                } else {
                    throw new \OutOfBoundsException("Row $rowIndex does not contain enough columns, " . ($maxIndex + 1) . " are required as this is the highest index defined in ImportPolicy");
                }
            }

            $entities[] = self::importRow($rowArray, $importPolicy);
        }

        return new EntityCollection($entities);
    }

    /**
     * @param string $data
     * @param ImportPolicy $importPolicy
     * @return EntityCollection
     */
    public static function fromString($data, ImportPolicy $importPolicy)
    {
        $entities = [];
        $rows = explode("\n", $data);

        $maxIndex = max(array_keys($importPolicy->getColumnDefinitions()));

        for ($i = $importPolicy->getHeaderOffset(); $i < count($rows); $i++) {
            $row = $rows[$i];

            if (empty($row)) {
                continue;
            }

            $rowArray = str_getcsv(
                $row,
                $importPolicy->getDelimiter(),
                $importPolicy->getEnclosure(),
                $importPolicy->getEscape()
            );

            if (count($rowArray) - 1 < $maxIndex) {
                if ($importPolicy->isQuiet()) {
                    continue;
                } else {
                    throw new \OutOfBoundsException("Row $i does not contain enough columns, " . ($maxIndex + 1) . " are required as this is the highest index defined in ImportPolicy");
                }
            }

            $entities[] = self::importRow($rowArray, $importPolicy);
        }

        return new EntityCollection($entities);
    }

    /**
     * @param array $row
     * @param ImportPolicy $importPolicy
     * @return Entity
     */
    private static function importRow(array $row, ImportPolicy $importPolicy)
    {
        $entity = new Entity();

        foreach ($importPolicy->getColumnDefinitions() as $columnDefinition) {
            $value = $row[$columnDefinition->getColumnIndex()];

            if ($importPolicy->isTrimValues()) {
                $value = trim($value);
            }

            $entity->set(
                $columnDefinition->getPropertyName(),
                $value
            );
        }

        return $entity;
    }

}