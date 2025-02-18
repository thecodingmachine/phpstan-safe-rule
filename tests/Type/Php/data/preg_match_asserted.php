<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

// Checking that preg_match and Safe\preg_match are equivalent
$pattern = '/H(.)ll(o) (World)?/';
$string = 'Hello World';

// when the return value is checked, we should have matches,
// unless the match-group itself is optional
$type = "array{0: string, 1: non-empty-string, 2: 'o', 3?: 'World'}";

// @phpstan-ignore-next-line - use of unsafe is intentional
$ret = \preg_match($pattern, $string, $matches);
assert($ret === 1);
\PHPStan\Testing\assertType($type, $matches);

$ret = \Safe\preg_match($pattern, $string, $matches);
assert($ret === 1);
\PHPStan\Testing\assertType($type, $matches);
