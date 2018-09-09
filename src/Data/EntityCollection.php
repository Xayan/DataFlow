<?php

namespace DataFlow\Data;

class EntityCollection
{
    private $entities = [];

    /**
     * EntityCollection constructor.
     * @param array $entities
     */
    public function __construct(array $entities = [])
    {
        $this->entities = $entities;
    }

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

        if (!isset($this->entities[(int)$index])) {
            throw new \OutOfBoundsException("There is no element with given index");
        }

        return $this->entities[(int)$index];
    }

    /**
     * @return Entity[]
     */
    public function getAll()
    {
        return $this->entities;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function has(Entity $entity)
    {
        foreach ($this->entities as $collectedEntity) {
            if ($entity === $collectedEntity) {
                return true;
            }
        }

        return false;
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
        foreach ($this->entities as $i => $collectedEntity) {
            if ($entity === $collectedEntity) {
                return $i;
            }
        }

        throw new \OutOfBoundsException("Entity not found");
    }

    /**
     * Call a function, passing each entity as an argument
     * Function has to have between 0 and 2 parameters
     * If 0: the array will be simply iterated
     * If 1: first argument will contain Entity
     * If 2: first argument will contain index, second will contain Entity
     *
     * @param $callable
     */
    public function each($callable)
    {
        $reflection = new \ReflectionFunction($callable);
        $parametersCount = count($reflection->getParameters());

        if ($parametersCount > 2) {
            throw new \InvalidArgumentException("Parameter \$callable should have between 0 and 2 parameters");
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
     * @throws \UnexpectedValueException
     */
    public function map($callable)
    {
        $newCollection = new EntityCollection();

        foreach ($this->entities as $i => $entity) {
            $newEntity = $callable($entity);

            if (!$newEntity instanceof Entity) {
                throw new \UnexpectedValueException("Parameter \$callable must always return a new Entity");
            }

            $newCollection->add($newEntity);
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