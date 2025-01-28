<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $phpLatest, $phpVersions, $nodeLatest, $nodeVersions;
$matrix = [];

foreach ($phpVersions as $phpVersion) {
    foreach ($nodeVersions as $nodeVersion) {
        $matrix[] = [
            'php_version' => $phpVersion,
            'node_version' => $nodeVersion,
            'latest' => $phpVersion === $phpLatest && $nodeVersion === $nodeLatest,
        ];
    }
}

echo 'include=' . json_encode($matrix);
