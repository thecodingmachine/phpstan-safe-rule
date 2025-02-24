<?php

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
json_decode("{}", flags: JSON_THROW_ON_ERROR);

// Test first class callable
json_decode(...);
