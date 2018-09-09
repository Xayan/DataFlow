<?php

namespace Test\Unit\DataFlow\Data;

use DataFlow\Data\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testGet()
    {
        $entity = new Entity(['testKey' => 'testValue']);

        $this->assertEquals('testValue', $entity->get('testKey'));
    }

    public function testGetAll()
    {
        $entity = new Entity($propertiesArray = ['testKey' => 'testValue']);

        $this->assertEquals($propertiesArray, $entity->getAll());

        $entity->set('anotherKey', 'test');

        $this->assertNotEquals($propertiesArray, $entity->getAll());
    }

    public function testSet()
    {
        $entity = new Entity();
        $entity->set('testKey', 'testValue');

        $this->assertEquals('testValue', $entity->get('testKey'));
    }

    public function testHas()
    {
        $entity = new Entity(['testKey' => 'testValue']);

        $this->assertTrue($entity->has('testKey'));
        $this->assertFalse($entity->has('anotherKey'));
    }

    public function testRemove()
    {
        $entity = new Entity(['testKey' => 'testValue']);
        $entity->remove('testKey');

        $this->assertFalse($entity->has('testKey'));
    }
}