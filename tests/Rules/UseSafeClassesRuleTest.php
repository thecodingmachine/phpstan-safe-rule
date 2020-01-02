<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;
use TheCodingMachine\Safe\PHPStan\Type\Php\ReplaceSafeFunctionsDynamicReturnTypeExtension;

class UseSafeClassesRuleTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new UseSafeClassesRule();
    }

    public function testDateTime()
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
