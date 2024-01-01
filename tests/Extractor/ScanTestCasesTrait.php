<?php

/*
 * This file is part of the markuspoerschke/extractum package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Extractum\Test\Extractor;

use Symfony\Component\Finder\Finder;

trait ScanTestCasesTrait
{
    /**
     * @return iterable<string, array<string, string>>
     */
    protected function scanForTestCases(string $directory): iterable
    {
        $finder = (new Finder())
            ->in($directory)
            ->name('*.html');

        foreach ($finder as $fileInfo) {
            $html = $fileInfo->getContents();
            $expected = include dirname($fileInfo->getRealPath()) . '/' . $fileInfo->getFilenameWithoutExtension() . '.php';

            yield $fileInfo->getFilenameWithoutExtension() => [$html, $expected];
        }
    }
}
