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
     * @var bool
     */
    private $trimValues = false;

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

    /***
     * @param array $header
     * @return ImportPolicy
     */
    public static function fromHeader(array $header)
    {
        $policy = new self();
        $policy->setHeaderOffset(1);

        foreach($header as $i => $key) {
            if(empty($key)) {
                continue;
            }

            $policy->addColumnDefinition(new ColumnDefinition($i, $key));
        }

        return $policy;
    }

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
     * @return bool
     */
    public function isTrimValues()
    {
        return $this->trimValues;
    }

    /**
     * @param bool $trimValues
     */
    public function setTrimValues($trimValues)
    {
        $this->trimValues = $trimValues;
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