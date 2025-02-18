<?php declare(strict_types = 1);

namespace TheCodingMachine\Safe\PHPStan\Type\Php;

use PHPStan\Testing\TypeInferenceTestCase;

class TypeAssertionsTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_match_unchecked.php');
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_match_checked.php');
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_match_asserted.php');
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_replace_return.php');
    }

    /**
     * @dataProvider dataFileAsserts
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../phpstan-safe-rule.neon'];
    }
}
