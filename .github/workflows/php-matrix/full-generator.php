<?php

include(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

global $phpLatest, $phpVersions, $nodeLatest, $nodeVersions, $notStableXdebugPhpVersions;
$matrix = [];

foreach ($phpVersions as $phpVersion) {
    $xdebugType = !in_array($phpVersion, $notStableXdebugPhpVersions) ? 'xdebug-stable' : 'xdebug';
    $matrix[] = [
        'php_version' => $phpVersion,
        'node_version' => 'x',
        'xdebug_type' => $xdebugType,
        'latest' => $phpVersion === $phpLatest,
    ];
    foreach ($nodeVersions as $nodeVersion) {
        $matrix[] = [
            'php_version' => $phpVersion,
            'node_version' => $nodeVersion,
            'xdebug_type' => $xdebugType,
            'latest' => $phpVersion === $phpLatest && $nodeVersion === $nodeLatest,
        ];
    }
}

echo 'include=' . json_encode($matrix);
