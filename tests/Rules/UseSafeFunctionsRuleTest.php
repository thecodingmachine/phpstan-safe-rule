<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;
use TheCodingMachine\Safe\PHPStan\Type\Php\ReplaceSafeFunctionsDynamicReturnTypeExtension;

class UseSafeFunctionsRuleTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new UseSafeFunctionsRule();
    }

    public function testCatch()
    {
        $this->analyse([__DIR__ . '/data/fopen.php'], [
            [
                "Function fopen is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\fopen;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                4,
            ],
        ]);
    }

    public function testNoCatchSafe()
    {
        $this->analyse([__DIR__ . '/data/safe_fopen.php'], []);
    }

    public function testExprCall()
    {
        $this->analyse([__DIR__ . '/data/undirect_call.php'], []);
    }

    public function testJSONDecodeNoCatchSafe()
    {
        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            $this->analyse([__DIR__ . '/data/safe_json_decode_for_7.3.0.php'], []);
        } else {
            $this->assertTrue(true);
        }
    }

    public function testJSONEncodeNoCatchSafe()
    {
        if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
            $this->analyse([__DIR__ . '/data/safe_json_encode_for_7.3.0.php'], []);
        } else {
            $this->assertTrue(true);
        }
    }
}
