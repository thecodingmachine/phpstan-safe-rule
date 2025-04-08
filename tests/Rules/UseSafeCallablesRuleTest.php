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

    public function testFirstClassCallable(): void
    {
        $this->analyse([__DIR__ . '/data/first_class_callable.php'], [
            [
                "Function json_encode is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\json_encode;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                3,
            ],
        ]);
    }
}
