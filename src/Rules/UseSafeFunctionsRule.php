<?php


namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use TheCodingMachine\Safe\PHPStan\Utils\FunctionListLoader;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;

/**
 * This rule checks that no "unsafe" functions are used in code.
 *
 * @implements Rule<Node\Expr\FuncCall>
 */
class UseSafeFunctionsRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\FuncCall::class;
    }

    /**
     * @param Node\Expr\FuncCall $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Name) {
            return [];
        }
        $functionName = $node->name->toString();
        $unsafeFunctions = FunctionListLoader::getFunctionList();

        if (isset($unsafeFunctions[$functionName])) {
            if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
                if ($functionName === "json_decode") {
                    if (count($node->args) == 4) {
                        if ($this->argValueIncludeJSONTHROWONERROR($node->args[3])) {
                            return [];
                        }
                    }
                }
                if ($functionName === "json_encode") {
                    if (count($node->args) >= 2) {
                        if ($this->argValueIncludeJSONTHROWONERROR($node->args[1])) {
                            return [];
                        }
                    }
                }
            }

            return ["Function $functionName is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\$functionName;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library."];
        }

        return [];
    }

    private function argValueIncludeJSONTHROWONERROR(Arg $arg): bool
    {
        $parseValue = function ($expr, array $options) use (&$parseValue): array {
            if ($expr instanceof Expr\BinaryOp\BitwiseOr) {
                return array_merge($parseValue($expr->left, $options), $parseValue($expr->right, $options));
            } elseif ($expr instanceof Expr\ConstFetch) {
                return array_merge($options, $expr->name->parts);
            } elseif ($expr instanceof Scalar\LNumber) {
                return array_merge($options, [$expr->value]);
            } else {
                return $options;
            }
        };
        $options = $parseValue($arg->value, []);

        if (in_array("JSON_THROW_ON_ERROR", $options)) {
            return true;
        }

        return in_array(true, array_map(function ($element) {
            // JSON_THROW_ON_ERROR == 4194304
            return ($element & 4194304) == 4194304;
        }, array_filter($options, function ($element) {
            return is_int($element);
        })));
    }
}
