<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

// preg_replace

// preg_replace with a string pattern should return a string
$x = \Safe\preg_replace('/foo/', 'bar', 'baz');
\PHPStan\Testing\assertType("string", $x);

// preg_replace with an array pattern should return an array
$x = \Safe\preg_replace(['/foo/'], ['bar'], ['baz']);
\PHPStan\Testing\assertType("array", $x);

// preg_replace_callback

// preg_replace_callback with a string pattern should return a string
$x = \Safe\preg_replace_callback('/foo/', fn (array $matches) => 'bar', 'baz');
\PHPStan\Testing\assertType("string", $x);

// preg_replace_callback with an array pattern should return an array
$x = \Safe\preg_replace_callback(['/foo/'], fn (array $matches) => 'bar', ['baz']);
\PHPStan\Testing\assertType("array", $x);


// preg_replace_callback_array

// preg_replace_callback_array with a string pattern should return a string
$x = \Safe\preg_replace_callback_array(['/foo/' => fn (array $matches) => 'bar'], 'baz');
\PHPStan\Testing\assertType("string", $x);

// preg_replace_callback with an array pattern should return an array
$x = \Safe\preg_replace_callback_array(['/foo/' => fn (array $matches) => 'bar'], ['baz']);
\PHPStan\Testing\assertType("array", $x);
