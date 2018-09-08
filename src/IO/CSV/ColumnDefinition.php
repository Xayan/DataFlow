<?php

namespace DataFlow\IO\CSV;

class ColumnDefinition
{
    /**
     * @var int
     */
    private $columnIndex;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * ColumnDefinition constructor.
     * @param $columnIndex
     * @param $propertyName
     */
    public function __construct($columnIndex, $propertyName)
    {
        $this->setColumnIndex($columnIndex);
        $this->setPropertyName($propertyName);
    }

    /**
     * @return int
     */
    public function getColumnIndex()
    {
        return $this->columnIndex;
    }

    /**
     * @param int $columnIndex
     */
    public function setColumnIndex($columnIndex)
    {
        if (!is_int($columnIndex) || !is_numeric($columnIndex)) {
            throw new \InvalidArgumentException("Parameter \$i must be an integer");
        }

        $this->columnIndex = $columnIndex;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     */
    public function setPropertyName($propertyName)
    {
        if(!is_string($propertyName)) {
            throw new \InvalidArgumentException("Parameter \$propertyName must be a string");
        }

        $this->propertyName = $propertyName;
    }
}