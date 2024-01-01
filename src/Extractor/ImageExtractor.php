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

use ML\JsonLD;
use Symfony\Component\DomCrawler\Crawler;

final class ImageExtractor extends AbstractExtractor
{
    private const CANDIDATE_SELECTORS = <<<SELECTOR
        meta[property='og:image'],
        meta[property='og:image:url'],
        meta[itemprop=image],
        meta[name='twitter:image:src'],
        meta[name='twitter:image'],
        meta[name='twitter:image0']
SELECTOR;

    public function extract(Crawler $crawler, ?JsonLD\Document $jsonLd): ?string
    {
        return $this->extractFromJsonLd($jsonLd) ?? $this->extractAttribute('content', self::CANDIDATE_SELECTORS, $crawler);
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

        $nodes = $graph->getNodes();
        foreach ($nodes as $node) {
            // dump($node->getId());
            // dump($node->getType());
            $type = $node->getType();
            if ($type instanceof JsonLD\NodeInterface) {
                if ($type->getId() === 'http://schema.org/WebPage') {
                    $images = $node->getProperty('http://schema.org/primaryImageOfPage');

                    if ($images instanceof JsonLD\NodeInterface) {
                        $images = [$images];
                    }

                    /** @var JsonLD\NodeInterface $image */
                    foreach ($images ?? [] as $image) {
                        $url = $image->getProperty('http://schema.org/url');
                        if ($url instanceof JsonLD\NodeInterface) {
                            return $url->getId();
                        }
                    }
                }
            }
        }

        return null;
    }
}
