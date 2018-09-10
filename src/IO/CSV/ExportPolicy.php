<?php

namespace DataFlow\IO\CSV;

use DataFlow\Data\Entity;

class ExportPolicy
{
    /**
     * @var ColumnDefinition[]
     */
    private $columnDefinitions = [];

    /**
     * @var bool
     */
    private $includeHeader = false;

    /**
     * @var bool
     */
    private $quiet = false;

    /**
     * @var string
     */
    private $delimiter = ',';

    /**
     * @var string
     */
    private $enclosure = '"';

    /**
     * @var string
     */
    private $escape = "\\";

    /**
     * @param Entity $entity
     * @return ExportPolicy
     */
    public static function fromEntity(Entity $entity)
    {
        $policy = new self();
        $policy->setIncludeHeader(true);
        $column = 0;

        foreach ($entity->getAll() as $property => $value) {
            $policy->addColumnDefinition(new ColumnDefinition($column++, $property));
        }

        return $policy;
    }

    /**
     * @param ColumnDefinition $columnDefinition
     */
    public function addColumnDefinition(ColumnDefinition $columnDefinition)
    {
        $this->columnDefinitions[] = $columnDefinition;
    }

    /**
     * @return ColumnDefinition[]
     */
    public function getColumnDefinitions()
    {
        return $this->columnDefinitions;
    }

    /**
     * @return bool
     */
    public function isIncludeHeader()
    {
        return $this->includeHeader;
    }

    /**
     * @param bool $includeHeader
     */
    public function setIncludeHeader($includeHeader)
    {
        $this->includeHeader = $includeHeader;
    }

    /**
     * @return bool
     */
    public function isQuiet()
    {
        return $this->quiet;
    }

    /**
     * @param bool $quiet
     */
    public function setQuiet($quiet)
    {
        $this->quiet = $quiet;
    }

    /**
     * @return string[]
     */
    public function getColumnNames()
    {
        return array_map(function(ColumnDefinition $columnDefinition) {
            return $columnDefinition->getPropertyName();
        }, $this->columnDefinitions);
    }

    /**
     * return int
     */
    public function getHighestIndex()
    {
        return array_reduce($this->columnDefinitions, function($highest, ColumnDefinition $columnDefinition) {
            return max($highest, $columnDefinition->getColumnIndex());
        }, 0);
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return string
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * @param string $escape
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
    }
}