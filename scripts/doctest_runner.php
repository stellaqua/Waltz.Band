<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

if ( isset($argv[1]) === false ) {
    usage($argv);
    exit(2);
} else if ( realpath($argv[1]) === false ) {
    echo "Error: Target path is not found.\n";
    usage($argv);
    exit(1);
} else {
    $targetPath = realpath($argv[1]);
}

$score = new Waltz\Score\DocTest($targetPath);

if ( $argc >= 3 ) {
    if ( isset($argv[2]) === true && realpath($argv[2]) === false ) {
        echo "Error: Test files path is not found.\n";
        usage($argv);
        exit(1);
    } else {
        $testFilesPath = realpath($argv[2]);
        $score->setTestFilesPath($testFilesPath);
    }
}

$band = new Waltz\Band\DocTest($score);

echo "\n";
$band->play();
echo "\n";

function usage($argv) {
    $scriptName = basename($argv[0]);
    echo "Usage: php $scriptName {target path} [{test files path}]\n";
}
