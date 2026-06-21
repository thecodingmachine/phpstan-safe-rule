<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

use function Safe\preg_match;

// PHPStan bug report: Safe\preg_match with "1 ===" identity check does NOT narrow $matches,
// even though plain truthy if (preg_match(...)) works correctly.
//
// Root cause: PHPStan's TypeSpecifier.php has a hardcoded literal-name check for 'preg_match'
// in resolveNormalizedIdentical(). This triggers specifyTypesInCondition() on the FuncCall, but
// the FuncCall is wrapped in AlwaysRememberedExpr at that point, so FunctionTypeSpecifyingExtension
// is never called and $matches is not narrowed.
//
// This affects ANY extension-based preg_match wrapper (e.g. Safe\preg_match) — not only native.

$pattern = '/H(.)ll(o) (World)?/';
$string = 'Hello World';

// This is what SHOULD happen (same as the truthy check in preg_match_checked.php):
$expectedType = "array{0: non-falsy-string, 1: non-empty-string, 2: 'o', 3?: 'World'}";

// BUG: $matches is NOT narrowed — actual type is array{}|array{...} instead of array{...}
if (1 === preg_match($pattern, $string, $matches)) {
    \PHPStan\Testing\assertType($expectedType, $matches);
}

// BUG: same issue via FQCN
if (1 === \Safe\preg_match($pattern, $string, $matches2)) {
    \PHPStan\Testing\assertType($expectedType, $matches2);
}
