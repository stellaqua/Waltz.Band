<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Parser\DocTest;

use Waltz\Band\Extractor\PhpDocComment;
use Waltz\Band\Parser\Result\PHPUnit as PHPUnitCodes;
use Waltz\Band\Parser\DocTest\Parser as DocTestParser;

/**
 * DocTestIterator
 *
 * @uses Iterator
 * @package Waltz.Band
 */
class DocTestIterator implements \Iterator
{
    /**
     * Extractor iterator
     *
     * @var PhpDocComment
     */
    private $_extractorIterator;

    /**
     * Constructor
     *
     * @param PhpDocComment $extractorIterator
     */
    public function __construct ( PhpDocComment $extractorIterator ) {
        $this->_extractorIterator = $extractorIterator->getIterator();
    }

    public function rewind ( ) {
        $this->_extractorIterator->rewind();
    }

    public function key ( ) {
        return $this->_extractorIterator->key();
    }

    public function current ( ) {
        $phpunitCodes = new PHPUnitCodes();
        $phpDocComments = $this->_extractorIterator->current();
        $targetPath = $phpDocComments->getFilePath();
        $phpunitCodes->setTargetPath($targetPath);
        $classNames = $phpDocComments->getClassNames();
        foreach ( $classNames as $className ) {
            $classDocComment = $phpDocComments->getClassDocComment($className);
            $setUpCodes = DocTestParser::parseSetUpCodes($classDocComment);
            foreach ( $setUpCodes as $setUpCode ) {
                $code = $setUpCode[DocTestParser::KEY_TEST_CODE];
                $phpunitCodes->addSetUpCode($className, $code);
            }
            $tearDownCodes = DocTestParser::parseTearDownCodes($classDocComment);
            foreach ( $tearDownCodes as $tearDownCode ) {
                $code = $tearDownCode[DocTestParser::KEY_TEST_CODE];
                $phpunitCodes->addTearDownCode($className, $code);
            }
            $methodDocComments = $phpDocComments->getMethodDocComments($className);
            foreach ( $methodDocComments as $methodName => $methodDocComment ) {
                $testCodes = DocTestParser::parseTestCodes($methodDocComment, $methodName);
                foreach ( $testCodes as $testCodeInfo ) {
                    $testCode = $testCodeInfo[DocTestParser::KEY_TEST_CODE];
                    $testName = $testCodeInfo[DocTestParser::KEY_TEST_NAME];
                    $phpunitCodes->addTestCode($className, $methodName, $testCode, $testName);
                }
            }
        }
        return $phpunitCodes;
    }

    public function next ( ) {
        $this->_extractorIterator->next();
    }

    public function valid ( ) {
        return !is_null($this->key());
    }
}
