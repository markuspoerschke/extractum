<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2021 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Extractor;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class LanguageExtractor extends AbstractExtractor
{
    private const CANDIDATE_SELECTORS = 'meta[name=lang],meta[http-equiv=content-language]';

    public function extract(Crawler $crawler): ?string
    {
        $lang = null;
        $htmlLang = $crawler->filter('html')->first()->attr('lang');
        if ($htmlLang !== null && $htmlLang !== '') {
            $lang = $htmlLang;
        }

        if ($lang === null) {
            $lang = $this->extractAttribute('content', self::CANDIDATE_SELECTORS, $crawler);
        }

        if ($lang === null) {
            return null;
        }

        return mb_strtolower(mb_substr($lang, 0, 2));
    }
}
