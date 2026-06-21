<?php

namespace PregReplace;

use function PHPStan\Testing\assertType;

class HelloWorld
{
    // `|` must be escaped FIRST to avoid double-escaping.
    private const array CHARACTERS_TO_ESCAPE = ['|', "'", "\n", "\r", '[', ']'];

    private const array ESCAPED_CHARACTERS = ['||', "|'", '|n', '|r', '|[', '|]'];

    private const string UNICODE_CHARACTER_REGEX = '/\\\\u(?<hexadecimalDigits>[0-9A-Fa-f]{4})/';

    /**
     * @return non-empty-string
     */
    private static function escapeValue(string|int|float $value): string
    {
        $escapedValue = sprintf(
            '\'%s\'',
            str_replace(
                self::CHARACTERS_TO_ESCAPE,
                self::ESCAPED_CHARACTERS,
                (string) $value,
            ),
        );
        assertType('non-falsy-string', $escapedValue);

        if (is_string($value) && str_contains($value, '\u')) {
            $escapedValue = preg_replace(
                self::UNICODE_CHARACTER_REGEX,
                '|0x$1',
                $escapedValue,
            );
        }
        assertType('non-falsy-string|null', $escapedValue);

        return $escapedValue;
    }
}
