<?php declare(strict_types = 1);

/*
Blatantly copy-pasted from PHPStan's source code but with isFunctionSupported changed
*/
namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PHPStan\Type\Php\RegexArrayShapeMatcher;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
use function in_array;
use function strtolower;

final class PregMatchTypeSpecifyingExtension implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

    private TypeSpecifier $typeSpecifier;

    public function __construct(
        private RegexArrayShapeMatcher $regexShapeMatcher,
    ) {
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    public function isFunctionSupported(FunctionReflection $functionReflection, FuncCall $node, TypeSpecifierContext $context): bool
    {
        return in_array(strtolower($functionReflection->getName()), ['safe\preg_match', 'safe\preg_match_all'], true) && !$context->null();
    }

    public function specifyTypes(FunctionReflection $functionReflection, FuncCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
    {
        $args = $node->getArgs();
        $patternArg = $args[0] ?? null;
        $subjectArg = $args[1] ?? null;
        $matchesArg = $args[2] ?? null;
        $flagsArg = $args[3] ?? null;

        $subjectTypes = new SpecifiedTypes();
        if ($patternArg === null) {
            return $subjectTypes;
        }

        if ($subjectArg !== null
            && $context->true()
            && $scope->getType($subjectArg->value)->isString()->yes()
        ) {
            $subjectType = $this->regexShapeMatcher->matchSubjectExpr($patternArg->value, $scope);
            if ($subjectType !== null) {
                $subjectTypes = $this->typeSpecifier->create(
                    $subjectArg->value,
                    $subjectType,
                    $context,
                    $scope,
                )->setRootExpr($node);
            }
        }

        if ($matchesArg === null) {
            return $subjectTypes;
        }

        $flagsType = null;
        if ($flagsArg !== null) {
            $flagsType = $scope->getType($flagsArg->value);
        }

        if ($context->true() && $context->falsey()) {
            $wasMatched = TrinaryLogic::createMaybe();
        } elseif ($context->true()) {
            $wasMatched = TrinaryLogic::createYes();
        } else {
            $wasMatched = TrinaryLogic::createNo();
        }

        if ($functionReflection->getName() === 'Safe\preg_match') {
            $matchedType = $this->regexShapeMatcher->matchExpr($patternArg->value, $flagsType, $wasMatched, $scope);
        } else {
            $matchedType = $this->regexShapeMatcher->matchAllExpr($patternArg->value, $flagsType, $wasMatched, $scope);
        }
        if ($matchedType === null) {
            return $subjectTypes;
        }

        $overwrite = false;
        if ($context->false()) {
            $overwrite = true;
            $context = $context->negate();
        }

        $types = $this->typeSpecifier->create(
            $matchesArg->value,
            $matchedType,
            $context,
            $scope,
        )->setRootExpr($node);
        if ($overwrite) {
            $types = $types->setAlwaysOverwriteTypes();
        }

        return $subjectTypes->unionWith($types);
    }
}
