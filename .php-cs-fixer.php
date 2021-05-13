<?php

$year = date('Y');

$headerComment = <<<EOF
This file is part of the markuspoerschke/extractum package.

(c) {$year} Markus Poerschke <markus@poerschke.nrw>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@Symfony' => true,
        'ordered_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'header_comment' => ['header' => $headerComment],
        'yoda_style' => false,
    ])
    ->setFinder($finder);

return $config;
