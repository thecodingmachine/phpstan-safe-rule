<?php


namespace TheCodingMachine\Safe\PHPStan\Utils;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;

class ClassListLoader
{
    /**
     * @var array<class-string,class-string>
     */
    private static $classes = [
        'DateTime' => 'DateTime',
        'DateTimeImmutable' => 'DateTimeImmutable',
    ];

    /**
     * @return class-string[]
     */
    public static function getClassList(): array
    {
        return self::$classes;
    }
}
