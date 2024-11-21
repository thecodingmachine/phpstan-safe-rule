<?php


namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use TheCodingMachine\Safe\PHPStan\Rules\Error\SafeClassRuleError;
use TheCodingMachine\Safe\PHPStan\Utils\ClassListLoader;

/**
 * This rule checks that no "unsafe" classes are instantiated in code.
 *
 * @implements Rule<Node\Expr\New_>
 */
class UseSafeClassesRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $classNode = $node->class;
        if (!$classNode instanceof Node\Name) {
            return [];
        }

        $className = $classNode->toString();
        $unsafeClasses = ClassListLoader::getClassList();

        if (isset($unsafeClasses[$className])) {
            return [
                new SafeClassRuleError($classNode, $node->getStartLine()),
            ];
        }

        return [];
    }
}
