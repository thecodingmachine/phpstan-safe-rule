<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PHPUnit\Framework\TestCase;

class ReplaceSafeFunctionsDynamicReturnTypeExtensionTest extends TestCase
{
    public function testWithStrings(): void
    {
        $x = \Safe\preg_replace('/foo/', 'bar', 'baz');

        $this->assertStringNotContainsString('foo', $x);
    }

    public function testWithArrays(): void
    {
        $x = \Safe\preg_replace(['/foo/'], ['bar'], ['baz']);

        $this->assertNotContains('foo', $x);
    }
}
