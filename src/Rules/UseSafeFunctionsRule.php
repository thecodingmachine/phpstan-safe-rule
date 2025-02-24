<?php


namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use TheCodingMachine\Safe\PHPStan\Rules\Error\SafeFunctionRuleError;
use TheCodingMachine\Safe\PHPStan\Utils\FunctionListLoader;

/**
 * This rule checks that no "unsafe" functions are used in code.
 *
 * @implements Rule<Node\Expr\FuncCall>
 */
class UseSafeFunctionsRule implements Rule
{
    /**
     * @see JSON_THROW_ON_ERROR
     */
    const JSON_THROW_ON_ERROR = 4194304;

    public function getNodeType(): string
    {
        return Node\Expr\FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Name) {
            return [];
        }
        $functionName = $node->name->toString();
        $unsafeFunctions = FunctionListLoader::getFunctionList();

        if (isset($unsafeFunctions[$functionName])) {
            if (! $node->isFirstClassCallable()) {
                if ($functionName === "json_decode" || $functionName === "json_encode") {
                    foreach ($node->args as $arg) {
                        if ($arg instanceof Node\Arg &&
                            $arg->name instanceof Node\Identifier &&
                            $arg->name->toLowerString() === "flags"
                        ) {
                            if ($this->argValueIncludeJSONTHROWONERROR($arg)) {
                                return [];
                            }
                        }
                    }
                }

                if ($functionName === "json_decode"
                    && $this->argValueIncludeJSONTHROWONERROR($node->getArgs()[3] ?? null)
                ) {
                    return [];
                }

                if ($functionName === "json_encode"
                    && $this->argValueIncludeJSONTHROWONERROR($node->getArgs()[1] ?? null)
                ) {
                    return [];
                }
            }

            return [new SafeFunctionRuleError($node->name, $node->getStartLine())];
        }

        return [];
    }

    private function argValueIncludeJSONTHROWONERROR(?Arg $arg): bool
    {
        if ($arg === null) {
            return false;
        }

        $parseValue = static function ($expr, array $options) use (&$parseValue): array {
            if ($expr instanceof Expr\BinaryOp\BitwiseOr) {
                return array_merge($parseValue($expr->left, $options), $parseValue($expr->right, $options));
            } elseif ($expr instanceof Expr\ConstFetch) {
                return array_merge($options, $expr->name->getParts());
            } elseif ($expr instanceof Scalar\Int_) {
                return array_merge($options, [$expr->value]);
            } else {
                return $options;
            }
        };
        $options = $parseValue($arg->value, []);

        if (in_array("JSON_THROW_ON_ERROR", $options, true)) {
            return true;
        }

        return in_array(true, array_map(function ($element) {
            return ($element & self::JSON_THROW_ON_ERROR) == self::JSON_THROW_ON_ERROR;
        }, array_filter($options, function ($element) {
            return is_int($element);
        })), true);
    }
}
