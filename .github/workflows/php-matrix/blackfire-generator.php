<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

$matrix = [];
$nodeVersions = NODE_VERSIONS;

foreach (PHP_VERSIONS as $phpVersion) {
    $xdebugType = !in_array($phpVersion, NOT_STABLE_XDEBUG_PHP_VERSIONS) ? 'xdebug-stable' : 'xdebug';
    $experimental = in_array($phpVersion, EXPERIMENTAL_PHP_VERSIONS);
    $matrix[] = [
        'php_version' => $phpVersion,
        'node_version' => 'x',
        'xdebug_type' => $xdebugType,
        'experimental' => $experimental,
        'latest' => $phpVersion === PHP_LATEST,
    ];
    foreach (NODE_VERSIONS as $nodeVersion) {
        if ($phpVersion === '7.2') {
            $nodeVersions = ['12'];
        }
        $matrix[] = [
            'php_version' => $phpVersion,
            'node_version' => $nodeVersion,
            'xdebug_type' => $xdebugType,
            'experimental' => $experimental,
            'latest' => $phpVersion === PHP_LATEST && $nodeVersion === NODE_LATEST,
        ];
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);
