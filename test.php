<?php

include 'vendor/autoload.php';

$extractor = new \Extractum\Extractor('de');
$html = file_get_contents(__DIR__.'/tests/Extractor/_tests/image/003.html');
$essence = $extractor->extract($html, 'https:/example.com');

dump($essence);
