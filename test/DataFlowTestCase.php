<?php

namespace Test\Unit;

use DataFlow\Data\Entity;
use DataFlow\Data\EntityCollection;
use PHPUnit\Framework\TestCase;

class DataFlowTestCase extends TestCase
{
    /**
     * @param $count
     * @param array $properties
     * @return EntityCollection
     */
    protected function createDummyEntityCollection($count, array $properties = [])
    {
        return new EntityCollection($this->createDummyEntities($count, $properties));
    }

    /**
     * @param $count
     * @param array $properties
     * @return array
     */
    protected function createDummyEntities($count, array $properties = [])
    {
        $entities = [];

        for ($i = 0; $i < $count; $i++) {
            $entities[] = $this->createDummyEntity($properties);
        }

        return $entities;
    }

    /**
     * @param array $properties
     * @return Entity
     */
    protected function createDummyEntity(array $properties = [])
    {
        return new Entity($properties);
    }
}