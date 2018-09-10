<?php

namespace Test\Unit\DataFlow\IO\CSV;

use DataFlow\Data\EntityCollection;
use DataFlow\IO\CSV\ColumnDefinition;
use DataFlow\IO\CSV\Export;
use DataFlow\IO\CSV\ExportPolicy;
use Test\Unit\DataFlowTestCase;

class ExportTest extends DataFlowTestCase
{
    public function testToString()
    {
        $people = [
            $this->createDummyEntity(['id' => 1, 'name' => 'John Doe', 'age' => 12]),
            $this->createDummyEntity(['id' => 2, 'name' => 'Johnny Bravo', 'age' => 30]),
            $this->createDummyEntity(['id' => 3, 'name' => 'Jimmy', 'age' => 40])
        ];

        $peopleCollection = new EntityCollection($people);

        $content = Export::toString($peopleCollection, ExportPolicy::fromEntity($people[0]));
        $expectedContent = file_get_contents('test/Resources/CSV/ExportTest_testToString_output.csv');

        $this->assertEquals($expectedContent, $content);
    }

    public function testToString_missingColumnDefinition()
    {
        $people = [
            $this->createDummyEntity(['id' => 1, 'name' => 'John Doe', 'age' => 12]),
            $this->createDummyEntity(['id' => 2, 'name' => 'Johnny Bravo', 'age' => 30]),
            $this->createDummyEntity(['id' => 3, 'name' => 'Jimmy', 'age' => 40])
        ];

        $peopleCollection = new EntityCollection($people);

        $exportPolicy = new ExportPolicy();
        $exportPolicy->setIncludeHeader(true);
        $exportPolicy->addColumnDefinition(new ColumnDefinition(0, 'id'));
        $exportPolicy->addColumnDefinition(new ColumnDefinition(2, 'age'));

        $content = Export::toString($peopleCollection, $exportPolicy);
        $expectedContent = file_get_contents('test/Resources/CSV/ExportTest_testToString_missingColumnDefinition_output.csv');

        $this->assertEquals($expectedContent, $content);
    }

    public function testToString_quiet()
    {
        $people = [
            $this->createDummyEntity(['id' => 1, 'name' => 'John Doe', 'age' => 12]),
            $this->createDummyEntity(['id' => 2, 'name' => 'Johnny Bravo', 'age' => 30]),
            $this->createDummyEntity(['id' => 3, 'name' => 'Jimmy', 'age' => 40])
        ];

        $peopleCollection = new EntityCollection($people);

        $exportPolicy = new ExportPolicy();
        $exportPolicy->setIncludeHeader(true);
        $exportPolicy->setQuiet(true);
        $exportPolicy->addColumnDefinition(new ColumnDefinition(0, 'id'));
        $exportPolicy->addColumnDefinition(new ColumnDefinition(1, 'nonexistent'));
        $exportPolicy->addColumnDefinition(new ColumnDefinition(2, 'age'));

        $content = Export::toString($peopleCollection, $exportPolicy);
        $expectedContent = file_get_contents('test/Resources/CSV/ExportTest_testToString_quiet_output.csv');

        $this->assertEquals($expectedContent, $content);
    }
}