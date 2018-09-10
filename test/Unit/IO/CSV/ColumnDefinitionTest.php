<?php

namespace Test\Unit\DataFlow\IO\CSV;

use DataFlow\IO\CSV\ColumnDefinition;
use Test\Unit\DataFlowTestCase;

class ColumnDefinitionTest extends DataFlowTestCase
{
    public function testSetColumnIndexInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new ColumnDefinition("test", "name");
    }

    public function testSetPropertyNameInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new ColumnDefinition(0, []);
    }
}