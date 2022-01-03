<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2022 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Extractor;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class DescriptionExtractor extends AbstractExtractor
{
    private const CANDIDATE_SELECTORS = 'meta[name=description],meta[property="og:description"],meta[itemprop="og:description"],meta[name="twitter:description"]';

    public function extract(Crawler $crawler): ?string
    {
        return $this->extractAttribute('content', self::CANDIDATE_SELECTORS, $crawler);
    }
}
