<?php declare(strict_types = 1);

/*
Blatantly copy-pasted from PHPStan's source code but with isFunctionSupported changed

https://github.com/phpstan/phpstan-src/blob/e664bed7b62e2a58d571fb631ddf47030914a2b5/src/Type/Php/PregMatchParameterOutTypeExtension.php
*/
namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\FunctionParameterOutTypeExtension;
use PHPStan\Type\Type;
use function in_array;

final class PregMatchParameterOutTypeExtension implements FunctionParameterOutTypeExtension
{

	public function __construct(
		private RegexArrayShapeMatcher $regexShapeMatcher,
	)
	{
	}

	public function isFunctionSupported(FunctionReflection $functionReflection, ParameterReflection $parameter): bool
	{
	    return in_array($functionReflection->getName(), ['Safe\preg_match', 'Safe\preg_match_all'], true)
			// the parameter is named different, depending on PHP version.
			&& in_array($parameter->getName(), ['subpatterns', 'matches'], true);
	}

	public function getParameterOutTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $funcCall, ParameterReflection $parameter, Scope $scope): ?Type
	{
		$args = $funcCall->getArgs();
		$patternArg = $args[0] ?? null;
		$matchesArg = $args[2] ?? null;
		$flagsArg = $args[3] ?? null;

		if (
			$patternArg === null || $matchesArg === null
		) {
			return null;
		}

		$flagsType = null;
		if ($flagsArg !== null) {
			$flagsType = $scope->getType($flagsArg->value);
		}

		if ($functionReflection->getName() === 'Safe\preg_match') {
			return $this->regexShapeMatcher->matchExpr($patternArg->value, $flagsType, TrinaryLogic::createMaybe(), $scope);
		}
		return $this->regexShapeMatcher->matchAllExpr($patternArg->value, $flagsType, TrinaryLogic::createMaybe(), $scope);
	}

}
