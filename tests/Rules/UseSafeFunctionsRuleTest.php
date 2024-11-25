<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template-extends RuleTestCase<UseSafeFunctionsRule>
 */
class UseSafeFunctionsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UseSafeFunctionsRule();
    }

    public function testCatch(): void
    {
        $this->analyse([__DIR__ . '/data/fopen.php'], [
            [
                "Function fopen is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\fopen;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                4,
            ],
        ]);
    }

    public function testNoCatchSafe(): void
    {
        $this->analyse([__DIR__ . '/data/safe_fopen.php'], []);
    }

    public function testExprCall(): void
    {
        $this->analyse([__DIR__ . '/data/undirect_call.php'], []);
    }

    public function testJSONDecodeNoCatchSafe(): void
    {
        $this->analyse([__DIR__ . '/data/safe_json_decode_for_7.3.0.php'], []);
    }

    public function testJSONEncodeNoCatchSafe(): void
    {
        $this->analyse([__DIR__ . '/data/safe_json_encode_for_7.3.0.php'], []);
    }
}
