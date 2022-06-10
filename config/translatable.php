<?php

use App\Support\{Language, Country};

return [
    'default_language' => Language::RU,
    'default_country' => Country::UA,
    'lang_corrector_map' => [
        'ua' => 'uk',
    ],
    'locales_lang_map' => [
        'uk_UA' => 'uk',
        'ru_UA' => 'ru',
        'uz_UZ' => 'uz',
        'ru_UZ' => 'ru',
    ],
];
