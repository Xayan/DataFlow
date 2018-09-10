<?php

namespace Test\Unit\DataFlow;

use Test\Unit\DataFlowTestCase;
use function DataFlow\str_putcsv;

class FunctionsTest extends DataFlowTestCase
{
    public function testStrPutCsv()
    {
        $output = str_putcsv(['test', '', '"']);

        $this->assertEquals('test,,""""', $output);
    }
}