<?php

include(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $osVersions, $phpLatest, $phpVersions, $nodeLatest, $nodeVersions, $notStableXdebugPhpVersions;
$matrix = [];

foreach ($osVersions as $osVersion) {
    foreach ($phpVersions as $phpVersion) {
        $xdebugType = !in_array($phpVersion, $notStableXdebugPhpVersions) ? 'xdebug-stable' : 'xdebug';
        $matrix[] = [
            'os' => $osVersion,
            'php_version' => $phpVersion,
            'node_version' => 'x',
            'xdebug_type' => $xdebugType,
            'latest' => $phpVersion === $phpLatest,
        ];
        foreach ($nodeVersions as $nodeVersion) {
            $matrix[] = [
                'os' => $osVersion,
                'php_version' => $phpVersion,
                'node_version' => $nodeVersion,
                'xdebug_type' => $xdebugType,
                'latest' => $phpVersion === $phpLatest && $nodeVersion === $nodeLatest,
            ];
        }
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);
