<?php

namespace Test\DataFlow\IO\CSV;

use DataFlow\Data\Entity;
use DataFlow\IO\CSV\ColumnDefinition;
use DataFlow\IO\CSV\Import;
use DataFlow\IO\CSV\ImportPolicy;
use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase
{
    public function testImport()
    {
        $importPolicy = new ImportPolicy();
        $importPolicy->setDelimiter(';');
        $importPolicy->setHeaderOffset(1);
        $importPolicy->setQuiet(true);
        $importPolicy->addColumnDefinition(new ColumnDefinition(0,  'id'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(1,  'firstName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(2,  'lastName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(3,  'age'));

        $people = Import::fromString(file_get_contents('test/resources/CSV/testImport_input.csv'), $importPolicy);

        $this->assertEquals(3, $people->count());

        foreach($people->getAll() as $person)
        {
            $this->assertTrue($person->has('id'));
            $this->assertTrue($person->has('firstName'));
            $this->assertTrue($person->has('lastName'));
            $this->assertTrue($person->has('age'));

            $this->assertNotEmpty($person->get('id'));
            $this->assertNotEmpty($person->get('firstName'));
            $this->assertNotEmpty($person->get('lastName'));
            $this->assertNotEmpty($person->get('age'));
        }
    }

    public function testImportOutOfBoundsException()
    {
        $this->expectException(\OutOfBoundsException::class);

        $importPolicy = new ImportPolicy();
        $importPolicy->setDelimiter(';');
        $importPolicy->setHeaderOffset(1);
        $importPolicy->setQuiet(false);
        $importPolicy->addColumnDefinition(new ColumnDefinition(0,  'id'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(1,  'firstName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(2,  'lastName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(3,  'age'));

        Import::fromString(file_get_contents('test/resources/CSV/testImport_input.csv'), $importPolicy);
    }

    public function testImportFromFile()
    {
        $importPolicy = new ImportPolicy();
        $importPolicy->setDelimiter(';');
        $importPolicy->setHeaderOffset(1);
        $importPolicy->setQuiet(true);
        $importPolicy->setTrimValues(true);
        $importPolicy->addColumnDefinition(new ColumnDefinition(0,  'id'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(1,  'firstName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(2,  'lastName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(3,  'age'));

        $people = Import::fromFile('test/resources/CSV/testImport_input.csv', $importPolicy);

        $newPeople = $people->filter(function(Entity $entity) {
            return $entity->has('age') && $entity->get('age') >= 18;
        })->map(function(Entity $entity) {
            $newEntity = new Entity($entity->getAll());

            $newEntity->set('fullName', $newEntity->get('firstName') . ' ' . $newEntity->get('lastName'));
        });

        $this->assertEquals(3, $people->count());

        foreach($people->getAll() as $person)
        {
            $this->assertTrue($person->has('id'));
            $this->assertTrue($person->has('firstName'));
            $this->assertTrue($person->has('lastName'));
            $this->assertTrue($person->has('age'));

            $this->assertNotEmpty($person->get('id'));
            $this->assertNotEmpty($person->get('firstName'));
            $this->assertNotEmpty($person->get('lastName'));
            $this->assertNotEmpty($person->get('age'));
        }
    }

    public function testImportFromFileOutOfBoundsException()
    {
        $this->expectException(\OutOfBoundsException::class);

        $importPolicy = new ImportPolicy();
        $importPolicy->setDelimiter(';');
        $importPolicy->setHeaderOffset(1);
        $importPolicy->setQuiet(false);
        $importPolicy->addColumnDefinition(new ColumnDefinition(0,  'id'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(1,  'firstName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(2,  'lastName'));
        $importPolicy->addColumnDefinition(new ColumnDefinition(3,  'age'));

        Import::fromFile('test/resources/CSV/testImport_input.csv', $importPolicy);
    }
}