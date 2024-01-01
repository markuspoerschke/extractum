<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\Scorer;

use Extractum\Scorer\DocumentScorer;
use Extractum\StopWords\StopWords;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class DocumentScorerTest extends TestCase
{
    public function testTopNodeIsReturned(): void
    {
        $topNode = (new DocumentScorer(StopWords::fromLanguageCode('en')))->score(
            new Crawler(file_get_contents(__DIR__ . '/_files/has_top_node.html'))
        );

        self::assertSame('top_node', $topNode->getAttribute('id'));
    }
}
