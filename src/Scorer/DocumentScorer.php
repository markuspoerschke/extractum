<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Scorer;

use DOMElement;
use Extractum\StopWords\StopWords;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
final class DocumentScorer
{
    private const SCORE_ATTRIBUTE_NAME = 'extractum-score';
    private const MINIMUM_WORD_COUNT = 5;
    private StopWords $stopWords;

    public function __construct(StopWords $stopWords)
    {
        $this->stopWords = $stopWords;
    }

    public function score(Crawler $crawler): ?DOMElement
    {
        /** @var DOMElement[] $parentNodes */
        $parentNodes = [];

        foreach ($crawler->filter('p, td, blockquote') as $node) {
            $stopWordStatistics = $this->stopWords->getStatistics($node->textContent);

            if ($stopWordStatistics->getWordCount() < self::MINIMUM_WORD_COUNT) {
                continue;
            }

            $score = (float) count($stopWordStatistics->getStopWords());

            $parent = $node->parentNode;
            if (!$parent instanceof DOMElement) {
                continue;
            }

            $this->addScoreToDomElement($parent, $score);
            $parentNodes[] = $parent;

            $grandParent = $parent->parentNode;
            if (!$grandParent instanceof DOMElement) {
                continue;
            }

            $this->addScoreToDomElement($grandParent, $score / 2);
            $parentNodes[] = $grandParent;
        }

        return $this->findTopNode($parentNodes);
    }

    private function addScoreToDomElement(DOMElement $element, float $addToScore): void
    {
        $element->setAttribute(
            self::SCORE_ATTRIBUTE_NAME,
            (string) ($this->getScoreFromDomElement($element) + $addToScore)
        );
    }

    private function getScoreFromDomElement(DOMElement $element): float
    {
        if ($element->hasAttribute(self::SCORE_ATTRIBUTE_NAME)) {
            return (float) $element->getAttribute(self::SCORE_ATTRIBUTE_NAME);
        }

        return 0.0;
    }

    /**
     * @param DOMElement[] $nodes
     */
    private function findTopNode(array $nodes): ?DOMElement
    {
        $topScore = 0.0;
        $topNode = null;

        foreach ($nodes as $node) {
            $score = $this->getScoreFromDomElement($node);
            if ($topNode === null || $score > $topScore) {
                $topNode = $node;
                $topScore = $score;
            }
        }

        return $topNode;
    }
}
