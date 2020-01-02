<?php


namespace TheCodingMachine\Safe\PHPStan\Utils;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;

class ClassListLoader
{
    private static $classes = [
        'DateTime' => 'DateTime',
        'DateTimeImmutable' => 'DateTimeImmutable',
    ];

    /**
     * @return string[]
     */
    public static function getClassList(): array
    {
        return self::$classes;
    }
}
