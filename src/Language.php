<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2023 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum;

final class Language
{
    private const SUPPORTED_LANGUAGES = [
        'ar',
        'bg',
        'ca',
        'cz',
        'da',
        'de',
        'el',
        'en',
        'eo',
        'es',
        'et',
        'fi',
        'fr',
        'hi',
        'hr',
        'hu',
        'id',
        'it',
        'ka',
        'lt',
        'lv',
        'nl',
        'no',
        'pl',
        'pt',
        'ro',
        'ru',
        'sk',
        'sv',
        'tr',
        'uk',
        'vi',
    ];

    private function __construct()
    {
        // left blank by intention
    }

    public static function isSupported(string $language): bool
    {
        return in_array($language, self::SUPPORTED_LANGUAGES);
    }

    public static function getSupported(): array
    {
        return self::SUPPORTED_LANGUAGES;
    }
}
