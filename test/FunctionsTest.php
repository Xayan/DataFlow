<?php

namespace Test\Unit\DataFlow;

use function DataFlow\str_putcsv;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function testStrPutCsv()
    {
        $output = str_putcsv(['test', '', '"']);

        $this->assertEquals('test,,""""', $output);
    }
}