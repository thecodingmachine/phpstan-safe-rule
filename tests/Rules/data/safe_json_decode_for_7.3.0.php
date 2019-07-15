<?php

json_decode("{}", true, 512, JSON_THROW_ON_ERROR);
json_decode("{}", true, 512, JSON_INVALID_UTF8_IGNORE | JSON_THROW_ON_ERROR);
json_decode("{}", true, 512, JSON_INVALID_UTF8_IGNORE | JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);

json_decode("{}", true, 512, 4194304);
json_decode("{}", true, 512, 1048576 | 4194304);
json_decode("{}", true, 512, 1048576 | 1 | 4194304);
