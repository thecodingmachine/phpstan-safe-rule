<?php declare(strict_types=1);


namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\BitwiseFlagHelper;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ConstantScalarType;
use PHPStan\Type\ConstantTypeHelper;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Safe\Exceptions\JsonException;

/**
 * @see \PHPStan\Type\Php\JsonThrowOnErrorDynamicReturnTypeExtension
 */
final class JsonDecodeDynamicReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    public function __construct(
        private readonly BitwiseFlagHelper $bitwiseFlagAnalyser,
    ) {
    }

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return strtolower($functionReflection->getName()) === 'safe\json_decode';
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): Type
    {
        $defaultReturnType = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $functionCall->getArgs(),
            $functionReflection->getVariants(),
        )->getReturnType();

        return $this->narrowTypeForJsonDecode($functionCall, $scope, $defaultReturnType);
    }

    private function narrowTypeForJsonDecode(FuncCall $funcCall, Scope $scope, Type $fallbackType): Type
    {
        $args = $funcCall->getArgs();
        $isForceArray = $this->isForceArray($funcCall, $scope);
        if (!isset($args[0])) {
            return $fallbackType;
        }

        $firstValueType = $scope->getType($args[0]->value);
        if ([] !== $firstValueType->getConstantStrings()) {
            $types = [];

            foreach ($firstValueType->getConstantStrings() as $constantString) {
                $types[] = $this->resolveConstantStringType($constantString, $isForceArray);
            }

            return TypeCombinator::union(...$types);
        }

        if ($isForceArray) {
            return TypeCombinator::remove($fallbackType, new ObjectWithoutClassType());
        }

        return $fallbackType;
    }

    /**
     * Is "json_decode(..., true)"?
     */
    private function isForceArray(FuncCall $funcCall, Scope $scope): bool
    {
        $args = $funcCall->getArgs();
        if (!isset($args[1])) {
            return false;
        }

        $secondArgType = $scope->getType($args[1]->value);
        $secondArgValue = 1 === \count($secondArgType->getConstantScalarValues()) ? $secondArgType->getConstantScalarValues()[0] : null;

        if (is_bool($secondArgValue)) {
            return $secondArgValue;
        }

        if ($secondArgValue !== null || !isset($args[3])) {
            return false;
        }

        // depends on used constants, @see https://www.php.net/manual/en/json.constants.php#constant.json-object-as-array
        return $this->bitwiseFlagAnalyser->bitwiseOrContainsConstant($args[3]->value, $scope, 'JSON_OBJECT_AS_ARRAY')->yes();
    }

    private function resolveConstantStringType(ConstantStringType $constantStringType, bool $isForceArray): Type
    {
        try {
            $decodedValue = \Safe\json_decode($constantStringType->getValue(), $isForceArray);
        } catch (JsonException) {
            return new NeverType();
        }

        return ConstantTypeHelper::getTypeFromValue($decodedValue);
    }
}
