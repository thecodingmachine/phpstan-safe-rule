<?php

use function Safe\fopen;
use function Safe\preg_replace;

fopen('foobar', 'r');

$x = preg_replace('/foo/', 'bar', 'baz');
$y = stripos($x, 'foo');

$x = Safe\preg_replace(['/foo/'], ['bar'], ['baz']);
