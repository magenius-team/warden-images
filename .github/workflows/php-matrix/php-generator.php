<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $osVersions, $phpLatest, $phpVersions;
$matrix = [];

foreach ($osVersions as $osVersion) {
    foreach ($phpVersions as $phpVersion) {
        $matrix[] = [
            'os' => $osVersion,
            'php_version' => $phpVersion,
            'latest' => $phpVersion === $phpLatest,
        ];
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);
