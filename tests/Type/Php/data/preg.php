<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

// Checking that preg_match and Safe\preg_match are equivalent
$pattern = '/H(.)ll(o) (World)?/';
$string = 'Hello World';
$type = "array{0?: string, 1?: non-empty-string, 2?: 'o', 3?: 'World'}";

// @phpstan-ignore-next-line - use of unsafe is intentional
\preg_match($pattern, $string, $matches);
\PHPStan\Testing\assertType($type, $matches);

\Safe\preg_match($pattern, $string, $matches);
\PHPStan\Testing\assertType($type, $matches);
