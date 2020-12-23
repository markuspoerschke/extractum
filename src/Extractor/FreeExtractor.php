<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2020 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Extractor;

use ML\JsonLD;
use Symfony\Component\DomCrawler\Crawler;

class FreeExtractor extends AbstractExtractor
{
    public function extract(Crawler $crawler, ?JsonLD\Document $jsonLd): bool
    {
        if ($jsonLd !== null && ($graph = $jsonLd->getGraph()) !== null) {
            $nodes = $graph->getNodes();
            if (isset($nodes[0])) {
                $property = $nodes[0]->getProperty('http://schema.org/isAccessibleForFree');
                if ($property instanceof JsonLD\TypedValue) {
                    if (strtolower($property->getValue()) === 'false') {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
