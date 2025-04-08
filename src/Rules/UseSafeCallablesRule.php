<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Node\FunctionCallableNode;
use TheCodingMachine\Safe\PHPStan\Rules\Error\SafeFunctionRuleError;
use TheCodingMachine\Safe\PHPStan\Utils\FunctionListLoader;

/**
 * This rule checks that no "unsafe" functions are used in code.
 *
 * @implements Rule<FunctionCallableNode>
 */
class UseSafeCallablesRule implements Rule
{
    /**
     * @see JSON_THROW_ON_ERROR
     */
    const JSON_THROW_ON_ERROR = 4194304;

    public function getNodeType(): string
    {
        return FunctionCallableNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $name = $node->getName();
        if (!$name instanceof Node\Name) {
            return [];
        }
        $functionName = $name->toString();
        $unsafeFunctions = FunctionListLoader::getFunctionList();

        if (isset($unsafeFunctions[$functionName])) {
            return [new SafeFunctionRuleError($name, $node->getStartLine())];
        }

        return [];
    }
}
