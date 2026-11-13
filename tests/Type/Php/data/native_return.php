<?php

namespace TheCodingMachine\Safe\PHPStan\Type\Php\data;

function nativeReturn(string $subject): void
{
    \PHPStan\Testing\assertType('list<string>', \Safe\file('foo.txt'));
    \PHPStan\Testing\assertType('list<string>', \Safe\scandir('.'));
    \PHPStan\Testing\assertType('list<string>', \Safe\preg_split('/,/', $subject));

    \PHPStan\Testing\assertType('string', \Safe\mb_convert_encoding($subject, 'UTF-8'));
    \PHPStan\Testing\assertType('array{string}', \Safe\mb_convert_encoding([$subject], 'UTF-8'));
}
