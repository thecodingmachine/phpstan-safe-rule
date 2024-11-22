<?php

namespace TheCodingMachine\Safe\PHPStan\Rules\Error;

use PhpParser\Node\Name;

class SafeClassRuleError extends SafeRuleError
{
    public function __construct(Name $className, int $line)
    {
        parent::__construct(
            "Class $className is unsafe to use. Its methods can return FALSE instead of throwing an exception. Please add 'use Safe\\$className;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
            $line,
        );
    }

    public function getIdentifier(): string
    {
        return self::IDENTIFIER_PREFIX . 'class';
    }
}
