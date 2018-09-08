<?php

namespace Test\Data;

use DataFlow\Data\Entity;
use DataFlow\Data\EntityCollection;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    public function testAdd()
    {
        $entity = new Entity();
        $entity->set('testKey', 'testValue');

        $entityCollection = new EntityCollection();
        $entityCollection->add($entity);

        $this->assertEquals(1, $entityCollection->count());

        $this->assertTrue($entityCollection->get(0)->has('testKey'));
        $this->assertEquals('testValue', $entityCollection->get(0)->get('testKey'));
    }
}