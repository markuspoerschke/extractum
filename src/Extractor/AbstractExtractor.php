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
use Extractum\Cleaner\StringCleaner;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
abstract class AbstractExtractor
{
    protected StringCleaner $stringCleaner;

    public function __construct()
    {
        $this->stringCleaner = new StringCleaner();
    }

    protected function extractAttribute(string $attribute, string $selector, Crawler $crawler): ?string
    {
        $candidates = $crawler->filter($selector);

        foreach ($candidates as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            if (!$node->hasAttribute($attribute)) {
                continue;
            }

            $value = $this->stringCleaner->clean($node->getAttribute($attribute));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }
}
