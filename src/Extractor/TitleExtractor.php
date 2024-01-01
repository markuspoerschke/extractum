<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Extractor;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class TitleExtractor extends AbstractExtractor
{
    private const CANDIDATE_SELECTORS = [
        'h1[class*=title]',
        'h1',
        'title',
    ];

    public function extract(Crawler $crawler): ?string
    {
        $metaTitle = $this->extractAttribute('content', 'meta[property="og:title"],meta[itemprop="og:title"]', $crawler);
        if ($metaTitle !== null) {
            return $metaTitle;
        }

        foreach (self::CANDIDATE_SELECTORS as $selector) {
            $text = $this->stringCleaner->clean(
                $crawler
                    ->filter($selector)
                    ->last()
                    ->text('')
            );
            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }
}
