<?php

namespace Test\DataFlow;

use DataFlow\Data\Entity;
use DataFlow\Data\EntityCollection;
use PHPUnit\Framework\TestCase;

class EntityCollectionTest extends TestCase
{
    public function testAdd()
    {
        $entity = new Entity();
        $entityCollection = new EntityCollection();
        $entityCollection->add($entity);

        $this->assertEquals(1, $entityCollection->count());
    }

    public function testGet()
    {
        $entity = new Entity();
        $entityCollection = new EntityCollection([$entity]);

        $this->assertEquals($entity, $entityCollection->get(0));
    }

    public function testGetInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $entityCollection = new EntityCollection();
        $entityCollection->get("invalid");
    }

    public function testGetOutOfBoundsException()
    {
        $this->expectException(\OutOfBoundsException::class);

        $entityCollection = new EntityCollection();
        $entityCollection->get(0);
    }

    public function testHas()
    {
        $entities = $this->createDummyEntities(2);
        $entityCollection = new EntityCollection([$entities[0]]);

        $this->assertTrue($entityCollection->has($entities[0]));
        $this->assertFalse($entityCollection->has($entities[1]));
    }

    public function testGetIndex()
    {
        $entities = $this->createDummyEntities(3);
        $entityCollection = new EntityCollection([$entities[0], $entities[1]]);

        $this->assertEquals(0, $entityCollection->getIndex($entities[0]));
        $this->assertEquals(1, $entityCollection->getIndex($entities[1]));
    }

    public function testGetIndexOutOfBoundsException()
    {
        $entities = $this->createDummyEntities(3);
        $entityCollection = new EntityCollection([$entities[0], $entities[1]]);

        // Third entity is not added to the collection, so an exception should be thrown
        $this->expectException(\OutOfBoundsException::class);
        $entityCollection->getIndex($entities[2]);
    }

    public function testEach()
    {
        $entities = $this->createDummyEntities(10);
        $entityCollection = new EntityCollection($entities);
        $counter = 0;

        $entityCollection->each(function(Entity $entity) use(&$counter) {
            $this->assertNotNull($entity);

            $counter++;
        });

        $this->assertEquals(count($entities), $counter);
    }

    public function testEachTwoArguments()
    {
        $entities = $this->createDummyEntities(10);
        $entityCollection = new EntityCollection($entities);
        $counter = 0;

        $entityCollection->each(function($i, Entity $entity) use(&$counter) {
            $this->assertInternalType('int', $i);
            $this->assertNotNull($entity);

            $counter++;
        });

        $this->assertEquals(count($entities), $counter);
    }

    public function testEachInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $entities = $this->createDummyEntities(10);
        $entityCollection = new EntityCollection($entities);

        $entityCollection->each(function($index, $entity, $exception) {});
    }

    public function testFilter()
    {
        $people = new EntityCollection([
            new Entity(['age' => 12]),
            new Entity(['age' => -2]),
            new Entity(['age' => 56]),
            new Entity(['age' => 23]),
            new Entity(['age' => 17]),
            new Entity(['age' => 33]),
        ]);
        
        $filteredPeople = $people->filter(function(Entity $person) {
            return $person->has('age') && $person->get('age') >= 18;
        });
        
        $this->assertEquals(3, $filteredPeople->count());

        foreach($filteredPeople->getAll() as $entity) {
            $this->assertTrue($entity->get('age') >= 18);
        }

        foreach($people->getAll() as $entity)
        {
            if($entity->get('age') < 18) {
                $this->assertNotContains($entity, $filteredPeople->getAll());
            }
        }
    }

    public function testMap()
    {
        $people = new EntityCollection([
            new Entity(['firstName' => 'John', 'lastName' => 'Doe']),
            new Entity(['firstName' => 'John', 'lastName' => 'Carmack']),
            new Entity(['firstName' => 'John', 'lastName' => 'Romero'])
        ]);

        $mappedPeople = $people->map(function (Entity $person) {
            return new Entity([
                'name' => $person->get('firstName') . ' ' . $person->get('lastName')
            ]);
        });

        foreach($mappedPeople->getAll() as $i => $person) {
            $expectedName = $people->get($i)->get('firstName') . ' ' . $people->get($i)->get('lastName');

            $this->assertEquals($expectedName, $person->get('name'));
        }
    }

    public function testMapUnexpectedValueException()
    {
        $this->expectException(\UnexpectedValueException::class);

        $entity = new Entity();
        $entityCollection = new EntityCollection([$entity]);

        $entityCollection->map(function(Entity $entity) {
            
        });
    }

    private function createDummyEntities($count, array $properties = [])
    {
        $entities = [];

        for($i = 0; $i < $count; $i++) {
            $entities[] = new Entity($properties);
        }

        return $entities;
    }
}