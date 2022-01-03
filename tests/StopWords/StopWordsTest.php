<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2022 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\StopWords;

use Extractum\StopWords\StopWords;
use PHPUnit\Framework\TestCase;

class StopWordsTest extends TestCase
{
    public function testCalculationOfStatistics(): void
    {
        $input = 'It is not that I’m so smart. But I stay with the questions much longer.';
        $statistics = StopWords::fromLanguageCode('en')->getStatistics($input);

        self::assertSame(15, $statistics->getWordCount());
        self::assertSame([
            'it',
            'is',
            'not',
            'that',
            'so',
            'but',
            'with',
            'the',
            'much',
        ], $statistics->getStopWords());
    }

    public function testCaluclationOfStatisticsWithGermanUmlauts(): void
    {
        $input = 'Der Bauer trägt die Früchte seiner Arbeit heim.';
        $statistics = StopWords::fromLanguageCode('de')->getStatistics($input);

        self::assertSame(8, $statistics->getWordCount());
        self::assertSame([
            'der',
            'trägt',
            'die',
            'seiner',
        ], $statistics->getStopWords());
    }
}
