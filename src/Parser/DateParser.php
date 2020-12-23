<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2020 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Parser;

use DateTimeImmutable;

class DateParser
{
    private const FORMAT = 'format';
    private const REPLACEMENT = 'replacement';
    public const TEST_INPUT = 'test_input';
    public const TEST_EXPECTED = 'test_expected';

    private const DEFAULT_DATE_FORMATS = [
        '/.*(\d{4}-\d{2}-\d{2} \d{2}:\d{2}).*/' => [
            self::FORMAT => 'Y-m-d H:i:s',
            self::REPLACEMENT => '$1:00',
            self::TEST_INPUT => 'Published on 2020-12-24 15:16.',
            self::TEST_EXPECTED => '2020-12-24 15:16:00',
        ],
        '/.*(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}).*/' => [
            self::FORMAT => 'Y-m-d H:i:s',
            self::REPLACEMENT => '$1 $2:00',
            self::TEST_INPUT => 'Published 2020-12-24T15:16.',
            self::TEST_EXPECTED => '2020-12-24 15:16:00',
        ],
        '/.*(\d{4}-\d{2}-\d{2}).*/' => [
            self::FORMAT => 'Y-m-d H:i:s',
            self::REPLACEMENT => '$1 00:00:00',
            self::TEST_INPUT => 'Published on 2020-12-24.',
            self::TEST_EXPECTED => '2020-12-24 00:00:00',
        ],
    ];

    private const DATE_FORMATS_BY_LANGUAGE = [
        'de' => [
            '/.*(\d{2})\.(\d{2})\.(\d{4}).*/' => [
                self::FORMAT => 'Y-m-d H:i:s',
                self::REPLACEMENT => '$3-$2-$1 00:00:00',
                self::TEST_INPUT => 'Veröffentlicht am 24.12.2020.',
                self::TEST_EXPECTED => '2020-12-24 00:00:00',
            ],
        ],
    ];

    public function parse(string $dateAsString, string $language = 'en'): ?DateTimeImmutable
    {
        foreach (static::getDateFormats($language) as $pattern => $options) {
            if (!preg_match($pattern, $dateAsString)) {
                continue;
            }

            $formattedDate = preg_replace($pattern, $options[self::REPLACEMENT], $dateAsString, 1);
            $date = DateTimeImmutable::createFromFormat($options[self::FORMAT], $formattedDate);

            return $date ?: null;
        }

        return null;
    }

    public static function getDateFormats(string $language): iterable
    {
        yield from self::DEFAULT_DATE_FORMATS;

        if (isset(self::DATE_FORMATS_BY_LANGUAGE[$language])) {
            yield from self::DATE_FORMATS_BY_LANGUAGE[$language];
        }
    }
}
