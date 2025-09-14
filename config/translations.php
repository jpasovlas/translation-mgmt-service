<?php

return [
    'locales' => explode(',', env('TRANSLATION_LOCALES_ARRAY', 'en,fr,es')),
    'tags'    => explode(',', env('TRANSLATION_TAGS_ARRAY', 'web,mobile,desktop')),
];