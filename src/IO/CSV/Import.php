<?php

namespace DataFlow\IO\CSV;

use DataFlow\Data\Entity;

class Import
{
    public static function exportToFile($data, $filename)
    {
        // TODO: Implement exportToFile() method.
    }

    public static function importFromFile($filename, $hasHeader)
    {
        // TODO: Implement importFromFile() method.
    }

    public static function import($data, ImportPolicy $importPolicy)
    {
        $rows = explode("\n", $data);

        $maxIndex = max(array_keys($importPolicy->getColumnDefinitions()));

        foreach ($rows as $i => $row) {
            $row = trim($row);

            if (empty($row)) {
                continue;
            }

            $rowArray = str_getcsv($row);

            if (count($rowArray) - 1 < $maxIndex) {
                if($importPolicy->isQuiet()) {
                    continue;
                } else {
                    throw new \OutOfBoundsException("Row $i does not contain enough columns, " . ($maxIndex + 1) . " are required as this is the highest index defined in ImportPolicy");
                }
            }

            $entity = self::importRow($rowArray, $importPolicy);
        }


    }

    public static function export($data)
    {
        // TODO: Implement export() method.
    }

    /**
     * @param array $row
     * @param ImportPolicy $importPolicy
     * @return Entity
     */
    private static function importRow(array $row, ImportPolicy $importPolicy)
    {
        $entity = new Entity();

        foreach($importPolicy->getColumnDefinitions() as $columnDefinition)
        {
            $entity->set(
                $columnDefinition->getPropertyName(),
                $row[$columnDefinition->getColumnIndex()]
            );
        }

        return $entity;
    }

}