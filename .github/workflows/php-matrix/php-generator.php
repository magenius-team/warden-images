<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $phpLatest, $phpVersions;
$matrix = [];

foreach ($phpVersions as $phpVersion) {
    $matrix[] = [
        'php_version' => $phpVersion,
        'latest' => $phpVersion === $phpLatest,
    ];
}

echo 'include=' . json_encode($matrix);
