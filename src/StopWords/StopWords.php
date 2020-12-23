<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2020 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\StopWords;

use Extractum\Cleaner\StringCleaner;
use Extractum\Exception\UnsupportedLanguageException;
use Extractum\Language;
use voku\helper\StopWords as VokuStopWords;

/**
 * @internal
 */
final class StopWords
{
    /**
     * @var string[]
     */
    private array $stopWords;

    private StringCleaner $cleaner;

    /**
     * @param string[] $stopWords
     */
    public function __construct(array $stopWords)
    {
        $this->cleaner = new StringCleaner();
        $this->stopWords = $stopWords;
    }

    public static function fromLanguageCode(string $language): self
    {
        if (!Language::isSupported($language)) {
            throw new UnsupportedLanguageException($language);
        }

        /** @var string[] $stopWords */
        $stopWords = (new VokuStopWords())->getStopWordsFromLanguage($language);

        return new self($stopWords);
    }

    public function getStatistics(string $text): StopWordsStatistics
    {
        $text = $this->cleaner->clean($text);
        $text = preg_replace('/[\|\@\<\>\[\]\"\'\.,-\/#\?!$%\^&\*\+;:{}=\-_`~()]/', '', $text);
        $text = mb_strtolower($text);

        $candidates = array_map('trim', explode(' ', $text));
        $stopWordsInContent = array_filter($candidates, fn (string $word) => in_array($word, $this->stopWords));
        $stopWordsInContent = array_values(array_unique($stopWordsInContent));

        return new StopWordsStatistics(count($candidates), $stopWordsInContent);
    }
}
