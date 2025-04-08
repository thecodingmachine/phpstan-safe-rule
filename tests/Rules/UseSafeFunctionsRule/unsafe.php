<?php

fopen('foobar', 'r');
json_decode("{}", flags: JSON_INVALID_UTF8_IGNORE);
json_encode([], flags: JSON_FORCE_OBJECT);
