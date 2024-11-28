<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @template-extends RuleTestCase<UseSafeClassesRule>
 */
class UseSafeClassesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UseSafeClassesRule();
    }

    public function testDateTime(): void
    {
        $this->analyse([__DIR__ . '/data/datetime.php'], [
            [
                "Class DateTime is unsafe to use. Its methods can return FALSE instead of throwing an exception. Please add 'use Safe\DateTime;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                3,
            ],
            [
                "Class DateTimeImmutable is unsafe to use. Its methods can return FALSE instead of throwing an exception. Please add 'use Safe\DateTimeImmutable;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                4,
            ],
        ]);
    }
}
