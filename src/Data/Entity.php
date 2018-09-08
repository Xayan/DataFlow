<?php

namespace DataFlow\Data;

class Entity
{
    private $children;

    /**
     * Entity constructor.
     */
    public function __construct()
    {
        $this->children = new EntityCollection();
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
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return property_exists($this, $key);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }
}