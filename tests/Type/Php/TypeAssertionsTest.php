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
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_replace_return.php');
        yield from self::gatherAssertTypes(__DIR__ . '/data/json_decode_return.php');
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

    /**
     * @return iterable<mixed>
     */
    public static function dataKnownProblems(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/data/preg_match_identity_check.php');
    }

    /**
     * @dataProvider dataKnownProblems
     */
    public function testKnownProblems(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        try {
            $this->assertFileAsserts($assertType, $file, ...$args);
            $this->fail(
                "Expected an assertion failure in $file, but it passed. ".
                "This is a known issue that should be fixed, but the test ".
                "should not fail until then."
            );
        } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
            $this->assertStringContainsString('Failed asserting that', $e->getMessage(), "Expected an assertion failure in $file, but got a different error: " . $e->getMessage());
        }
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../phpstan-safe-rule.neon'];
    }
}
