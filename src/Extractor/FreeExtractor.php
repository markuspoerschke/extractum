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

class FreeExtractor extends AbstractExtractor
{
    public function extract(?JsonLD\Document $jsonLd): bool
    {
        if ($jsonLd === null) {
            return true;
        }

        $graph = $jsonLd->getGraph();
        if ($graph === null) {
            return true;
        }

        $nodes = $graph->getNodes();
        if (isset($nodes[0])) {
            $property = $nodes[0]->getProperty('http://schema.org/isAccessibleForFree');
            if ($property instanceof JsonLD\TypedValue) {
                if (strtolower($property->getValue()) === 'false') {
                    return false;
                }
            }
        }

        return true;
    }
}
