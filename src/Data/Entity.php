<?php

namespace DataFlow\Data;

class Entity
{
    private $children;

    /**
     * Entity constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->children = new EntityCollection();

        foreach($properties as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->$key;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $reflectionClass = new \ReflectionClass($this);
        $values = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $values[$property->getName()] = $property->getValue();
        }

        return $values;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($this->$key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return property_exists($this, $key);
    }
}