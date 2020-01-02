<?php


namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use TheCodingMachine\Safe\PHPStan\Utils\ClassListLoader;
use TheCodingMachine\Safe\PHPStan\Utils\FunctionListLoader;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

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

    /**
     * @param Node\Expr\New_ $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classNode = $node->class;
        if (!$classNode instanceof Node\Name) {
            return [];
        }

        $className = $classNode->toString();
        $unsafeClasses = ClassListLoader::getClassList();

        if (isset($unsafeClasses[$className])) {
            return ["Class $className is unsafe to use. Its methods can return FALSE instead of throwing an exception. Please add 'use Safe\\$className;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library."];
        }

        return [];
    }
}
