<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2023 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\Cleaner;

use Extractum\Cleaner\StringCleaner;
use PHPUnit\Framework\TestCase;

class StringCleanerTest extends TestCase
{
    public function testStringIsCleaned(): void
    {
        $input = <<<STRING
A   string
with 
multiple

lines.
STRING;

        self::assertSame(
            'A string with multiple lines.',
            (new StringCleaner())->clean($input)
        );
    }
}
