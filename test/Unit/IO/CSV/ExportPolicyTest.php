<?php

namespace Test\Util\DataFlow\IO\CSV;

use DataFlow\IO\CSV\ExportPolicy;
use Test\Unit\DataFlowTestCase;

class ExportPolicyTest extends DataFlowTestCase
{
    public function testGetColumnNames()
    {
        $entity = $this->createDummyEntity(['id' => 1, 'age' => 10]);

        $columnDefinition = ExportPolicy::fromEntity($entity);

        $this->assertEquals(['id', 'age'], $columnDefinition->getColumnNames());
    }
}