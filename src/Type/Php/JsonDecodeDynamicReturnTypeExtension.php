<?php declare(strict_types=1);


namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\NeverType;
use PHPStan\Type\Php\JsonThrowOnErrorDynamicReturnTypeExtension;
use PHPStan\Type\Type;

/**
 * @see \PHPStan\Type\Php\JsonThrowOnErrorDynamicReturnTypeExtension
 */
final class JsonDecodeDynamicReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    public function __construct(
        private readonly JsonThrowOnErrorDynamicReturnTypeExtension $phpstanCheck,
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return strtolower($functionReflection->getName()) === 'safe\json_decode';
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): Type
    {
        $functionReflection = $this->reflectionProvider->getFunction(new Name('json_decode'), null);
        $result = $this->phpstanCheck->getTypeFromFunctionCall($functionReflection, $functionCall, $scope);

        // if PHPStan reports null and there is a json error, then an invalid constant string was passed
        if ($result->isNull()->yes() && JSON_ERROR_NONE !== json_last_error()) {
            return new NeverType();
        }

        return $result;
    }
}
