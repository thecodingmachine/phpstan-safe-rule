<?php

namespace TheCodingMachine\Safe\PHPStan\Utils;

use PHPUnit\Framework\TestCase;

class FunctionListLoaderTest extends TestCase
{
    public function testGetFunctionList(): void
    {
        $functions = FunctionListLoader::getFunctionList();
        $this->assertArrayHasKey('fopen', $functions);
        $this->assertEquals('fopen', $functions['fopen']);
    }
}
