<?php

namespace DataFlow\IO\CSV;

class ImportPolicy
{
    /**
     * @var int
     */
    private $headerOffset = 0;

    /**
     * @var ColumnDefinition[]
     */
    private $columnDefinitions = [];

    /**
     * @var bool
     */
    private $quiet = false;

    /**
     * @return int
     */
    public function getHeaderOffset()
    {
        return $this->headerOffset;
    }

    /**
     * @param int $headerOffset
     */
    public function setHeaderOffset($headerOffset)
    {
        $this->headerOffset = $headerOffset;
    }

    /**
     * @param int $columnIndex
     * @param string $propertyName
     */
    public function addColumnDefinition($columnIndex, $propertyName)
    {

        $this->columnDefinitions[$columnIndex] = $propertyName;
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
}