<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2021 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Essence;

use JsonSerializable;

final class Link implements JsonSerializable
{
    private string $href;
    private string $text;

    public function __construct(string $href, string $text)
    {
        $this->href = $href;
        $this->text = $text;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function jsonSerialize(): array
    {
        return [
            'href' => $this->href,
            'text' => $this->text,
        ];
    }
}
