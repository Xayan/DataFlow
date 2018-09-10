<?php

namespace Test\Unit\DataFlow\IO\CSV;

use DataFlow\IO\CSV\ImportPolicy;
use Test\Unit\DataFlowTestCase;

class ImportPolicyTest extends DataFlowTestCase
{
    public function testFromHeader()
    {
        $policy = ImportPolicy::fromHeader([0 => 'id', 1 => 'name', 2 => '', 3 => 'age']);

        $this->assertEquals(1, $policy->getHeaderOffset());
        $this->assertEquals(0, $policy->getColumnDefinitions()[0]->getColumnIndex());
        $this->assertEquals(1, $policy->getColumnDefinitions()[1]->getColumnIndex());
        $this->assertEquals(3, $policy->getColumnDefinitions()[2]->getColumnIndex());
        $this->assertEquals('id', $policy->getColumnDefinitions()[0]->getPropertyName());
        $this->assertEquals('name', $policy->getColumnDefinitions()[1]->getPropertyName());
        $this->assertEquals('age', $policy->getColumnDefinitions()[2]->getPropertyName());

    }
}