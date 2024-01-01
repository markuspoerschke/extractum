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

use Extractum\Essence\Link;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link as CrawlerLink;

/**
 * @internal
 */
final class LinksExtractor extends AbstractExtractor
{
    /**
     * @return Link[]
     */
    public function extract(Crawler $crawler): array
    {
        return array_map(function (CrawlerLink $link): Link {
            $linkNode = $link->getNode();
            $linkText = $linkNode->textContent;
            if ($linkNode->hasAttribute('title')) {
                $linkText = $linkNode->getAttribute('title');
            }

            return new Link($link->getUri(), $this->stringCleaner->clean($linkText));
        }, $crawler->filter('a')->links());
    }
}
