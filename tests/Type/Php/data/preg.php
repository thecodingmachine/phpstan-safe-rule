<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

$pattern = '/H(.)ll(o) (World)?/';
$string = 'Hello World';
$type = "array{0?: string, 1?: non-empty-string, 2?: 'o', 3?: 'World'}";

// Checking that preg_match and Safe\preg_match are equivalent
\preg_match($pattern, $string, $matches);
\PHPStan\Testing\assertType($type, $matches);

\Safe\preg_match($pattern, $string, $matches);
\PHPStan\Testing\assertType($type, $matches);
