<?php


namespace TheCodingMachine\Safe\PHPStan\Utils;

class FunctionListLoader
{
    /**
     * @var array<string, string>
     */
    private static array $functions;

    /**
     * @return array<string, string>
     */
    public static function getFunctionList(): array
    {
        return self::$functions ??= self::fetchIndexedFunctions();
    }

    /**
     * @return array<string, string>
     */
    private static function fetchIndexedFunctions(): array
    {
        if (\file_exists(__DIR__ . '/../../../safe/generated/functionsList.php')) {
            $functions = require __DIR__ . '/../../../safe/generated/functionsList.php';
        } elseif (\file_exists(__DIR__ . '/../../vendor/thecodingmachine/safe/generated/functionsList.php')) {
            $functions = require __DIR__ . '/../../vendor/thecodingmachine/safe/generated/functionsList.php';
        } else {
            throw new \RuntimeException('Could not find thecodingmachine/safe\'s functionsList.php file.');
        }

        if (!is_array($functions)) {
            throw new \RuntimeException('The functions list should be an array.');
        }

        $indexedFunctions = [];

        foreach ($functions as $function) {
            if (!is_string($function)) {
                throw new \RuntimeException('The functions list should contain only strings, got ' . get_debug_type($function));
            }

            // Let's index these functions by their name
            $indexedFunctions[$function] = $function;
        }
        
        return $indexedFunctions;
    }
}
