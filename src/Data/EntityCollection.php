<?php

namespace DataFlow\Data;

class EntityCollection
{
    private $entities = [];

    /**
     * Add an entity to the collection
     *
     * @param Entity $entity
     */
    public function add(Entity $entity)
    {
        $this->entities[] = $entity;
    }

    /**
     * Get entity at given index
     *
     * @param $index
     * @return Entity
     */
    public function get($index)
    {
        if (!is_int($index) || !is_numeric($index)) {
            throw new \InvalidArgumentException("Parameter \$i must be an integer");
        }

        return $this->entities[(int)$index];
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function has(Entity $entity)
    {
        return array_search($entity, $this->entities) !== false;
    }

    /**
     * Returns index of given entity
     *
     * @param Entity $entity
     * @return int|string
     * @throws \OutOfBoundsException
     */
    public function getIndex(Entity $entity)
    {
        $index = array_search($entity, $this->entities);

        if ($index === false) {
            throw new \OutOfBoundsException("Entity not found");
        }

        return $index;
    }

    /**
     * Call a function, passing each entity as an argument
     * Function has to have one parameter or two
     * In case of one parameter, it will be calles with the entity as an argument
     * In the other case, the first argument will be the index of an element, and then the element itself
     *
     * @param $callable
     */
    public function each($callable)
    {
        $reflection = new \ReflectionFunction($callable);
        $parametersCount = count($reflection->getParameters());

        if ($parametersCount < 1 || $parametersCount) {
            throw new \InvalidArgumentException("Parameter \$callback should have only one or two arguments");
        }

        foreach ($this->entities as $i => $entity) {
            if ($parametersCount === 1) {
                $callable($entity);
            } elseif ($parametersCount === 2) {
                $callable($i, $entity);
            }
        }
    }

    /**
     * Filters the collection using a callable function and returns a new, filtered collection
     * If you want your entity to be copied to the new collection, please return true in your function
     *
     * @param $callable
     * @return EntityCollection
     */
    public function filter($callable)
    {
        $newCollection = new EntityCollection();

        foreach ($this->entities as $i => $entity) {
            $match = $callable($entity);

            if ($match) {
                $newCollection->add($entity);
            }
        }

        return $newCollection;
    }

    /**
     * Maps the collection using a callable function and returns a new, modified collection
     *
     * @param $callable
     * @return EntityCollection
     */
    public function map($callable)
    {
        $newCollection = new EntityCollection();

        foreach ($this->entities as $i => $entity) {
            $newCollection->add($callable($entity));
        }

        return $newCollection;
    }

    /**
     * Return number of entities in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }
}