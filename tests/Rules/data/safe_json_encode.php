<?php

// Various combinations of flags
json_encode([], JSON_THROW_ON_ERROR, 512);
json_encode([], JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR, 512);
json_encode([], JSON_FORCE_OBJECT | JSON_INVALID_UTF8_IGNORE | JSON_THROW_ON_ERROR, 512);

// Named arguments
json_encode([], flags: JSON_THROW_ON_ERROR);
