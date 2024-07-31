<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

$matrix = [];
$nodeVersions = NODE_VERSIONS;

foreach (PHP_VERSIONS as $phpVersion) {
    if ($phpVersion === '7.2') {
        $nodeVersions = ['12'];
    }
    $experimental = in_array($phpVersion, EXPERIMENTAL_PHP_VERSIONS);
    foreach ($nodeVersions as $nodeVersion) {
        $matrix[] = [
            'php_version' => $phpVersion,
            'node_version' => $nodeVersion,
            'experimental' => $experimental,
            'latest' => $phpVersion === PHP_LATEST && $nodeVersion === NODE_LATEST,
        ];
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);
