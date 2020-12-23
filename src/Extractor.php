<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2020 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum;

use Extractum\Cleaner\TextBodyCleaner;
use Extractum\Exception\UnsupportedLanguageException;
use Extractum\Extractor\DateExtractor;
use Extractum\Extractor\DescriptionExtractor;
use Extractum\Extractor\FreeExtractor;
use Extractum\Extractor\ImageExtractor;
use Extractum\Extractor\LanguageExtractor;
use Extractum\Extractor\LinksExtractor;
use Extractum\Extractor\TextExtractor;
use Extractum\Extractor\TitleExtractor;
use Extractum\Helper\ExtractJsonLdTrait;
use Extractum\Parser\DateParser;
use Extractum\Scorer\DocumentScorer;
use Extractum\StopWords\StopWords;
use ML\JsonLD;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class Extractor
{
    use ExtractJsonLdTrait;

    private string $fallbackLanguage = 'en';
    private DateExtractor $dateExtractor;
    private DateParser $dateParser;
    private DescriptionExtractor $descriptionExtractor;
    private ImageExtractor $imageExtractor;
    private LanguageExtractor $languageExtractor;
    private LinksExtractor $linksExtractor;
    private TextExtractor $textExtractor;
    private TitleExtractor $titleExtractor;
    private TextBodyCleaner $topNodeCleaner;
    private FreeExtractor $freeExtractor;

    public function __construct(string $fallbackLanguage)
    {
        if (!Language::isSupported($fallbackLanguage)) {
            throw new UnsupportedLanguageException($fallbackLanguage);
        }

        $this->fallbackLanguage = $fallbackLanguage;
        $this->dateExtractor = new DateExtractor();
        $this->dateParser = new DateParser();
        $this->descriptionExtractor = new DescriptionExtractor();
        $this->imageExtractor = new ImageExtractor();
        $this->languageExtractor = new LanguageExtractor();
        $this->linksExtractor = new LinksExtractor();
        $this->textExtractor = new TextExtractor();
        $this->titleExtractor = new TitleExtractor();
        $this->topNodeCleaner = new TextBodyCleaner();
        $this->freeExtractor = new FreeExtractor();
    }

    public function extract(string $html, string $uri): Essence
    {
        $document = new Crawler($html, $uri);
        $scorerLanguage = $language = $this->languageExtractor->extract($document) ?? $this->fallbackLanguage;

        if (!Language::isSupported($scorerLanguage)) {
            $scorerLanguage = $this->fallbackLanguage;
        }

        $documentScorer = new DocumentScorer(StopWords::fromLanguageCode($scorerLanguage));
        $topNode = $this->topNodeCleaner->clean(new Crawler($documentScorer->score($document), $uri));

        $jsonLd = $this->extractJsonLd($document);

        return $this->createEssence($document, $jsonLd, $topNode, $language, $scorerLanguage);
    }

    private function createEssence(Crawler $document, ?JsonLD\Document $jsonLd, Crawler $topNode, string $language, string $scorerLanguage): Essence
    {
        $date = $this->dateExtractor->extract($document, $jsonLd);
        $essence = (new Essence())
            ->setDate($date)
            ->setDescription($this->descriptionExtractor->extract($document))
            ->setImage($this->imageExtractor->extract($document, $jsonLd))
            ->setLanguage($language)
            ->setLinks($this->linksExtractor->extract($topNode))
            ->setText($this->textExtractor->extract($topNode))
            ->setTitle($this->titleExtractor->extract($document))
            ->setFree($this->freeExtractor->extract($jsonLd))
        ;

        if ($date !== null) {
            $essence->setParsedDate($this->dateParser->parse($date, $scorerLanguage));
        }

        return $essence;
    }
}
