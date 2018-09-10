<?php

namespace DataFlow\IO\CSV;

use DataFlow\Data\Entity;
use DataFlow\Data\EntityCollection;
use function DataFlow\str_putcsv;

class Export
{
    /**
     * Writes export content into a stream (a file handler, for example)
     *
     * @param $stream
     * @param EntityCollection $entityCollection
     * @param ExportPolicy $exportPolicy
     * @return void
     */
    public static function toStream($stream, EntityCollection $entityCollection, ExportPolicy $exportPolicy)
    {
        if(gettype($stream) !== 'resource' || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException("Argument \$fileHandler must be a resource of type 'stream' (a file handler)");
        }

        if ($exportPolicy->isIncludeHeader()) {
            $rows[] = fputcsv(
                $stream,
                self::mapExportPolicyToArray($exportPolicy),
                $exportPolicy->getDelimiter(),
                $exportPolicy->getEnclosure(),
                $exportPolicy->getEscape()
            );
        }

        foreach ($entityCollection->getAll() as $entity) {
            $rows[] = fputcsv(
                $stream,
                self::mapEntityToArray($entity, $exportPolicy),
                $exportPolicy->getDelimiter(),
                $exportPolicy->getEnclosure(),
                $exportPolicy->getEscape()
            );
        }
    }

    /**
     * @param EntityCollection $entityCollection
     * @param ExportPolicy $exportPolicy
     * @return string
     */
    public static function toString(EntityCollection $entityCollection, ExportPolicy $exportPolicy)
    {
        $rows = [];

        if ($exportPolicy->isIncludeHeader()) {
            $rows[] = str_putcsv(
                self::mapExportPolicyToArray($exportPolicy),
                $exportPolicy->getDelimiter(),
                $exportPolicy->getEnclosure(),
                $exportPolicy->getEscape()
            );
        }

        foreach ($entityCollection->getAll() as $entity) {
            $rows[] = str_putcsv(
                self::mapEntityToArray($entity, $exportPolicy),
                $exportPolicy->getDelimiter(),
                $exportPolicy->getEnclosure(),
                $exportPolicy->getEscape()
            );
        }

        return implode("\n", $rows);
    }

    /**
     * @param ExportPolicy $exportPolicy
     * @return array
     */
    private static function mapExportPolicyToArray(ExportPolicy $exportPolicy)
    {
        $mappedPolicy = [];

        foreach($exportPolicy->getColumnDefinitions() as $columnDefinition) {
            $mappedPolicy[$columnDefinition->getColumnIndex()] = $columnDefinition->getPropertyName();
        }

        // If definitions of columns are missing some columns, they need to be filled with empty strings
        for ($i = 0; $i <= $exportPolicy->getHighestIndex(); $i++) {
            if(!isset($mappedPolicy[$i])) {
                $mappedPolicy[$i] = '';
            }
        }

        ksort($mappedPolicy);

        return $mappedPolicy;
    }

    /**
     * @param Entity $entity
     * @param ExportPolicy $exportPolicy
     * @return array
     */
    private static function mapEntityToArray(Entity $entity, ExportPolicy $exportPolicy)
    {
        $mappedEntity = [];

        foreach($exportPolicy->getColumnDefinitions() as $columnDefinition) {
            if($entity->has($columnDefinition->getPropertyName())) {
                $mappedEntity[$columnDefinition->getColumnIndex()] = $entity->get($columnDefinition->getPropertyName());
            } else {
                if($exportPolicy->isQuiet()) {
                    $mappedEntity[$columnDefinition->getColumnIndex()] = '';
                } else {
                    throw new \OutOfBoundsException("Entity does not contain property '{$columnDefinition->getPropertyName()}'");
                }
            }
        }

        // If definitions of columns are missing some columns, they need to be filled with empty strings
        for ($i = 0; $i <= $exportPolicy->getHighestIndex(); $i++) {
            if(!isset($mappedEntity[$i])) {
                $mappedEntity[$i] = '';
            }
        }

        ksort($mappedEntity);

        return $mappedEntity;
    }
}