<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

// preg_replace with a string pattern should return a string
$x = \Safe\preg_replace('/foo/', 'bar', 'baz');
\PHPStan\Testing\assertType("string", $x);

// preg_replace with an array pattern should return an array
$x = \Safe\preg_replace(['/foo/'], ['bar'], ['baz']);
\PHPStan\Testing\assertType("array", $x);
