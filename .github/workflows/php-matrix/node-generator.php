<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'constants.php');

$matrix = [];

foreach (PHP_VERSIONS as $phpVersion) {
    $experimental = in_array($phpVersion, EXPERIMENTAL_PHP_VERSIONS);
    foreach (NODE_VERSIONS as $nodeVersion) {
        $matrix[] = [
            'php_version' => $phpVersion,
            'node_version' => $nodeVersion,
            'experimental' => $experimental,
            'latest' => $phpVersion === PHP_LATEST && $nodeVersion === NODE_LATEST,
        ];
    }
}

echo 'matrix=' . json_encode(['include' => $matrix]);