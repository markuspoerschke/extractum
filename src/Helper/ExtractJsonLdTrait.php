<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Helper;

use ML\JsonLD\Document;
use ML\JsonLD\JsonLD;
use Symfony\Component\DomCrawler\Crawler;

trait ExtractJsonLdTrait
{
    protected function extractJsonLd(Crawler $crawler): ?Document
    {
        $scriptTag = $crawler->filter('script[type="application/ld+json"]');
        if ($scriptTag->count() === 0) {
            return null;
        }

        $jsonString = $scriptTag->text();

        return JsonLD::getDocument($jsonString);
    }
}
