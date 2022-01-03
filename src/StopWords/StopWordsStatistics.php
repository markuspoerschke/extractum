<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2022 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\StopWords;

/**
 * @internal
 */
final class StopWordsStatistics
{
    private int $wordCount;

    /**
     * @var string[]
     */
    private array $stopWords;

    /**
     * @param string[] $stopWords
     */
    public function __construct(int $wordCount, array $stopWords)
    {
        $this->wordCount = $wordCount;
        $this->stopWords = $stopWords;
    }

    public function getWordCount(): int
    {
        return $this->wordCount;
    }

    /**
     * @return array<int, string>
     */
    public function getStopWords(): array
    {
        return $this->stopWords;
    }
}
