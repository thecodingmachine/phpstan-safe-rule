<?php

namespace TheCodingMachine\Safe\PHPStan\Rules\Error;

use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\LineRuleError;
use PHPStan\Rules\RuleError;

abstract class SafeRuleError implements RuleError, LineRuleError, IdentifierRuleError
{
    protected const IDENTIFIER_PREFIX = 'theCodingMachineSafe.';
    
    private string $message;
    private int $line;

    public function __construct(string $message, int $line)
    {
        $this->message = $message;
        $this->line = $line;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
