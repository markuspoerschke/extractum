<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2022 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\Extractor;

use Extractum\Extractor\DescriptionExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class DescriptionExtractorTest extends TestCase
{
    use ScanTestCasesTrait;

    /**
     * @dataProvider provideTestData
     */
    public function testDescriptionIsExtracted(string $html, $expected): void
    {
        $crawler = new Crawler($html, 'https://www.example.com');
        $actual = (new DescriptionExtractor())->extract($crawler);

        self::assertSame($expected, $actual);
    }

    public function provideTestData(): iterable
    {
        yield from $this->scanForTestCases(__DIR__ . '/_tests/description');
    }
}
