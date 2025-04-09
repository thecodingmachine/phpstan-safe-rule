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

    public function testUnsafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeFunctionsRule/unsafe.php'], [
            [
                "Function fopen is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\fopen;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                3,
            ],
            [
                "Function json_decode is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\json_decode;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                4,
            ],
            [
                "Function json_encode is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\json_encode;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                5,
            ],
        ]);
    }

    public function testUseSafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeFunctionsRule/use_safe.php'], []);
    }

    public function testNativeSafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeFunctionsRule/native_safe.php'], []);
    }

    public function testExpr(): void
    {
        $this->analyse([__DIR__ . '/UseSafeFunctionsRule/expr.php'], []);
    }
}
