<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

if ( isset($argv[1]) === false ) {
    $scriptName = basename($argv[0]);
    echo "Usage: php $scriptName {dirpath or filepath}\n";
    exit(2);
} else if ( realpath($argv[1]) === false ) {
    echo "Error: Target path is not found.\n";
    exit(1);
} else {
    $targetPath = realpath($argv[1]);
}

$score = new Waltz\Score\Simple($targetPath);
$band = new Waltz\Band\Simple($score);

echo "\n";
$band->play();
echo "\n";
