<?php

var_dump();

// Test various combinations of flags
json_decode("{}", true, 512, JSON_THROW_ON_ERROR);
json_decode("{}", true, 512, JSON_INVALID_UTF8_IGNORE | JSON_THROW_ON_ERROR);
json_decode("{}", true, 512, JSON_INVALID_UTF8_IGNORE | JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);

// Test raw integers too
json_decode("{}", true, 512, 4194304);
json_decode("{}", true, 512, 1048576 | 4194304);
json_decode("{}", true, 512, 1048576 | 1 | 4194304);

// Test named arguments instead of positional
json_decode("{}", flags: JSON_THROW_ON_ERROR);

// Various combinations of flags
json_encode([], JSON_THROW_ON_ERROR, 512);
json_encode([], JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR, 512);
json_encode([], JSON_FORCE_OBJECT | JSON_INVALID_UTF8_IGNORE | JSON_THROW_ON_ERROR, 512);

// Test raw integers too
json_encode([], 4194304, 512);
json_encode([], 16 | 4194304, 512);
json_encode([], 16 | 1048576 | 4194304, 512);

// Named arguments
json_encode([], flags: JSON_THROW_ON_ERROR);
