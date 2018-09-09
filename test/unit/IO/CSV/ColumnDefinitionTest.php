<?php

namespace Test\Unit\DataFlow\IO\CSV;

use DataFlow\IO\CSV\ColumnDefinition;
use PHPUnit\Framework\TestCase;

class ColumnDefinitionTest extends TestCase
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