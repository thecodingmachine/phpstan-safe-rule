<?php

namespace TheCodingMachine\Safe\PHPStan\Rules\Error;

use PhpParser\Node\Name;

class SafeFunctionRuleError extends SafeRuleError
{
    public function __construct(Name $nodeName, int $line)
    {
        $functionName = $nodeName->toString();

        parent::__construct(
            "Function $functionName is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\$functionName;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
            $line,
        );
    }

    public function getIdentifier(): string
    {
        return self::IDENTIFIER_PREFIX . 'function';
    }
}
