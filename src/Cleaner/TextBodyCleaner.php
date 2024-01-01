<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Cleaner;

use Symfony\Component\DomCrawler\Crawler;

final class TextBodyCleaner
{
    private const REMOVE_ELEMENTS_SELECTOR = <<<SELECTOR
        aside,
        figure,
        h1,
        script,
        style
SELECTOR;

    public function clean(Crawler $crawler): Crawler
    {
        if ($crawler->count() === 0) {
            return $crawler;
        }

        $crawler = new Crawler($crawler->html(), $crawler->getUri(), $crawler->getBaseHref());

        foreach ($crawler->filter(self::REMOVE_ELEMENTS_SELECTOR) as $node) {
            $parent = $node->parentNode;

            if ($parent !== null) {
                $parent->removeChild($node);
            }
        }

        return $crawler;
    }
}
