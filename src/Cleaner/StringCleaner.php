<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2023 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Cleaner;

/**
 * @internal
 */
final class StringCleaner
{
    public function clean(string $string): string
    {
        $string = str_replace(["\n", "\r", "\t"], ' ', $string);
        $string = trim(preg_replace('/(?:\s{2,}+|[^\S ])/', ' ', $string));

        return $string;
    }
}
