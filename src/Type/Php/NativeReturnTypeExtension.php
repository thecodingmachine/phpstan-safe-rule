<?php declare(strict_types=1);

namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * Infers what PHPStan knows about the native function, minus the error values Safe throws on.
 */
final class NativeReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    private const FUNCTIONS_WITH_OWN_EXTENSION = [
        'safe\json_decode',
        'safe\preg_replace',
        'safe\preg_replace_callback',
        'safe\preg_replace_callback_array',
    ];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        $name = strtolower($functionReflection->getName());

        return str_starts_with($name, 'safe\\')
            && ! in_array($name, self::FUNCTIONS_WITH_OWN_EXTENSION, true)
            && $this->reflectionProvider->hasFunction(new Name(substr($name, 5)), null);
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): ?Type
    {
        if ($functionCall->isFirstClassCallable()) {
            return null;
        }

        $nativeCall = new FuncCall(
            new Name\FullyQualified(substr($functionReflection->getName(), 5)),
            $functionCall->getArgs(),
            $functionCall->getAttributes(),
        );
        $safeReturnType = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $functionCall->getArgs(),
            $functionReflection->getVariants(),
        )->getReturnType();

        $returnType = TypeCombinator::intersect($scope->getType($nativeCall), $safeReturnType);

        return $returnType instanceof NeverType ? null : $returnType;
    }
}
