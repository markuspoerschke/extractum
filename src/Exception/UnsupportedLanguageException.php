<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2020 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Exception;

use Extractum\Language;
use InvalidArgumentException;

class UnsupportedLanguageException extends InvalidArgumentException implements ExtractumException
{
    public const CODE = 1605469402;

    public function __construct(string $language)
    {
        parent::__construct(
            sprintf(
                'The language code "%s" is not supported. Valid languages are %s.',
                $language,
                implode(', ', Language::getSupported())
            ),
            self::CODE
        );
    }
}
