<?php

namespace DataFlow\Data;

class Entity
{
    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var EntityCollection
     */
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
        return $this->properties[$key];
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->properties;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($this->properties[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->properties[$key]);
    }
}