<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\Parser;

use DateTimeImmutable;
use Extractum\Language;
use Extractum\Parser\DateParser;
use PHPUnit\Framework\TestCase;

class DateParserTest extends TestCase
{
    /**
     * @dataProvider provideTestData
     */
    public function testThatDateIsParsed(string $inputString, string $language, ?DateTimeImmutable $expected): void
    {
        $actual = (new DateParser())->parse($inputString, $language);
        self::assertEquals($expected, $actual);
    }

    public function testThatDateCannotBeParsed(): void
    {
        $actual = (new DateParser())->parse('lorem ipsum', 'en');
        self::assertNull($actual);
    }

    public function provideTestData(): iterable
    {
        foreach (Language::getSupported() as $language) {
            foreach (DateParser::getDateFormats($language) as $dateFormat) {
                yield [$dateFormat[DateParser::TEST_INPUT], $language, new DateTimeImmutable($dateFormat[DateParser::TEST_EXPECTED])];
            }
        }
    }
}
