<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $osVersions, $phpLatest, $phpVersions, $nodeLatest, $nodeVersions;
$matrix = [];

foreach ($osVersions as $osVersion) {
    foreach ($phpVersions as $phpVersion) {
        foreach ($nodeVersions as $nodeVersion) {
            $matrix[] = [
                'os' => $osVersion,
                'php_version' => $phpVersion,
                'node_version' => $nodeVersion,
                'latest' => $phpVersion === $phpLatest && $nodeVersion === $nodeLatest,
            ];
        }
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);
