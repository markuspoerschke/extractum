<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2021 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Extractor;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class TextExtractor
{
    public function extract(Crawler $crawler): ?string
    {
        return trim($crawler->text(''));
    }
}
