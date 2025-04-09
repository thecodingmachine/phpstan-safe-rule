<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template-extends RuleTestCase<UseSafeCallablesRule>
 */
class UseSafeCallablesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UseSafeCallablesRule();
    }

    public function testUnsafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeCallablesRule/unsafe.php'], [
            [
                "Function json_encode is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\json_encode;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                3,
            ],
        ]);
    }

    public function testUseSafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeCallablesRule/use_safe.php'], []);
    }

    public function testNativeSafe(): void
    {
        $this->analyse([__DIR__ . '/UseSafeCallablesRule/native_safe.php'], []);
    }

    public function testExpr(): void
    {
        $this->analyse([__DIR__ . '/UseSafeCallablesRule/expr.php'], []);
    }
}
