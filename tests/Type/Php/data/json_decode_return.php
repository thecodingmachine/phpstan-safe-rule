<?php
$value = \Safe\json_decode('null');
\PHPStan\Testing\assertType('null', $value);

$value = \Safe\json_decode('false');
\PHPStan\Testing\assertType('false', $value);

$value = \Safe\json_decode('[]');
\PHPStan\Testing\assertType('array{}', $value);

$value = \Safe\json_decode('{}');
\PHPStan\Testing\assertType('stdClass', $value);

$value = \Safe\json_decode('{}', true);
\PHPStan\Testing\assertType('array{}', $value);

$value = \Safe\json_decode('{}', flags: JSON_OBJECT_AS_ARRAY);
\PHPStan\Testing\assertType('array{}', $value);

$value = \Safe\json_decode('{"foo": "bar"}');
\PHPStan\Testing\assertType('stdClass', $value);

$value = \Safe\json_decode('{"foo": "bar"}', true);
\PHPStan\Testing\assertType("array{foo: 'bar'}", $value);

$value = \Safe\json_decode('{', true);
\PHPStan\Testing\assertType('*NEVER*', $value);

function(string $json): void {
    $value = \Safe\json_decode($json);
    \PHPStan\Testing\assertType('mixed', $value);

    $value = \Safe\json_decode($json, true);
    \PHPStan\Testing\assertType('mixed~object', $value);
};
