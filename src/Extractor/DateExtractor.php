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

use DOMElement;
use DOMNode;
use ML\JsonLD;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class DateExtractor extends AbstractExtractor
{
    private const CANDIDATE_SELECTOR = <<<SELECTOR
        meta[property='article:published_time'],
        meta[itemprop*='datePublished'],
        meta[name='dcterms.modified'],
        meta[name='dcterms.date'],
        meta[name='DC.date.issued'],  meta[name='dc.date.issued'],
        meta[name='dc.date.modified'], meta[name='dc.date.created'],
        meta[name='DC.date'],
        meta[name='DC.Date'],
        meta[name='dc.date'],
        meta[name='date'],
        time[itemprop*='pubDate'],
        time[itemprop*='pubdate'],
        span[itemprop*='datePublished'],
        span[property*='datePublished'],
        p[itemprop*='datePublished'],
        p[property*='datePublished'],
        div[itemprop*='datePublished'],
        div[property*='datePublished'],
        li[itemprop*='datePublished'],
        li[property*='datePublished'],
        time,
        span[class*='date'],
        p[class*='date'],
        div[class*='date']
SELECTOR;

    public function extract(Crawler $crawler, ?JsonLD\Document $jsonLd): ?string
    {
        return $this->extractFromJsonLd($jsonLd) ?? $this->extractFromCrawler($crawler);
    }

    private function extractFromCrawler(Crawler $crawler): ?string
    {
        $candidates = $crawler->filter(self::CANDIDATE_SELECTOR);
        /** @var DOMNode|DOMElement $node */
        foreach ($candidates as $node) {
            if ($node instanceof DOMElement) {
                if ($node->hasAttribute('content')) {
                    $content = $this->stringCleaner->clean($node->getAttribute('content'));

                    if ($content !== '') {
                        return $content;
                    }
                }

                if ($node->hasAttribute('datetime')) {
                    $datetime = $this->stringCleaner->clean($node->getAttribute('datetime'));

                    if ($datetime !== '') {
                        return $datetime;
                    }
                }
            }

            $text = $this->stringCleaner->clean($node->textContent);
            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }

    private function extractFromJsonLd(?JsonLD\Document $jsonLd): ?string
    {
        if ($jsonLd === null) {
            return null;
        }

        $graph = $jsonLd->getGraph();
        if ($graph === null) {
            return null;
        }

        foreach ($graph->getNodes() as $node) {
            /** @var JsonLD\TypedValue|null $property */
            $property = $node->getProperty('http://schema.org/datePublished');
            if ($property instanceof JsonLD\TypedValue) {
                return $property->getValue();
            }
        }

        return null;
    }
}
