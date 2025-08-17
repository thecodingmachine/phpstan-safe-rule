<?php declare(strict_types = 1);


namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeUtils;

/**
 * This file has been copy-pasted from PHPStan's source code but with isFunctionSupported changed.
 * For now, only preg_* functions are supported.
 * @See https://github.com/phpstan/phpstan-src/blob/2.1.x/src/Type/Php/ReplaceFunctionsDynamicReturnTypeExtension.php
 */
class ReplaceSafeFunctionsDynamicReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{

    /** @var array<string, int> The function name associated with the typed argument position used to type the return */
    private array $functions = [
        'Safe\preg_replace' => 2,
        'Safe\preg_replace_callback' => 2,
        'Safe\preg_replace_callback_array' => 1,
    ];

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return array_key_exists($functionReflection->getName(), $this->functions);
    }

    public function getTypeFromFunctionCall(
        FunctionReflection $functionReflection,
        FuncCall $functionCall,
        Scope $scope
    ): Type {
        $type = $this->getPreliminarilyResolvedTypeFromFunctionCall($functionReflection, $functionCall, $scope);

        $possibleTypes = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $functionCall->getArgs(),
            $functionReflection->getVariants()
        )
            ->getReturnType();

        if (TypeCombinator::containsNull($possibleTypes)) {
            $type = TypeCombinator::addNull($type);
        }

        return $type;
    }

    private function getPreliminarilyResolvedTypeFromFunctionCall(
        FunctionReflection $functionReflection,
        FuncCall $functionCall,
        Scope $scope
    ): Type {
        $argumentPosition = $this->functions[$functionReflection->getName()];

        $args = $functionCall->getArgs();
        $variants = $functionReflection->getVariants();
        $defaultReturnType = ParametersAcceptorSelector::selectFromArgs($scope, $args, $variants)
            ->getReturnType();

        if (count($args) <= $argumentPosition) {
            return $defaultReturnType;
        }

        $subjectArgument = $args[$argumentPosition];
        $subjectArgumentType = $scope->getType($subjectArgument->value);
        $mixedType = new MixedType();
        if ($subjectArgumentType->isSuperTypeOf($mixedType)->yes()) {
            return TypeUtils::toBenevolentUnion($defaultReturnType);
        }

        $stringType = new StringType();
        if ($stringType->isSuperTypeOf($subjectArgumentType)->yes()) {
            return $stringType;
        }

        $arrayType = new ArrayType($mixedType, $mixedType);
        if ($arrayType->isSuperTypeOf($subjectArgumentType)->yes()) {
            return $arrayType;
        }

        return $defaultReturnType;
    }
}
