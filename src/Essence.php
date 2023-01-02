<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2023 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum;

use DateTimeImmutable;
use Extractum\Essence\Link;
use JsonSerializable;

final class Essence implements JsonSerializable
{
    private ?string $date = null;
    private ?string $description = null;

    /**
     * Indicates if content is hidden behind a "pay wall".
     *
     * If true, then the content can be accessed free of costs.
     * If false, then the user needs to pay a fee to view the content.
     */
    private bool $free = true;
    private ?string $image = null;
    private ?string $language = null;

    /**
     * @var Link[]
     */
    private array $links = [];
    private ?DateTimeImmutable $parsedDate = null;
    private ?string $text = null;
    private ?string $title = null;

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): Essence
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Essence
    {
        $this->description = $description;

        return $this;
    }

    public function isFree(): bool
    {
        return $this->free;
    }

    public function setFree(bool $free): Essence
    {
        $this->free = $free;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): Essence
    {
        $this->image = $image;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): Essence
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks(array $links): Essence
    {
        $this->links = $links;

        return $this;
    }

    public function getParsedDate(): ?DateTimeImmutable
    {
        return $this->parsedDate;
    }

    public function setParsedDate(?DateTimeImmutable $parsedDate): Essence
    {
        $this->parsedDate = $parsedDate;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Essence
    {
        $this->text = $text;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Essence
    {
        $this->title = $title;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->date,
            'description' => $this->description,
            'free' => $this->free,
            'image' => $this->image,
            'language' => $this->language,
            'links' => $this->links,
            'parsedDate' => $this->parsedDate,
            'text' => $this->text,
            'title' => $this->title,
        ];
    }
}
