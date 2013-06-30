<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Runner\Simple;

use Waltz\Stagehand\FileUtility;
use Waltz\Band\Runner\Simple\SimpleListener;

/**
 * SimpleIterator
 *
 * @uses Iterator
 * @package Waltz.Band
 */
class SimpleIterator implements \Iterator
{
    /**
     * Target file paths
     *
     * @var Iterator
     */
    private $_targetFilePaths;

    /**
     * Constructor
     *
     * @param Iterator $targetFilePaths
     */
    public function __construct ( \Iterator $targetFilePaths ) {
        $this->_targetFilePaths = $targetFilePaths;
    }

    public function rewind ( ) {
        $this->_targetFilePaths->rewind();
    }

    public function key ( ) {
        return $this->_targetFilePaths->key();
    }

    public function current ( ) {
        $suite = new \PHPUnit_Framework_TestSuite();
        $filePath = $this->_targetFilePaths->current();
        require_once $filePath;

        $fileObjects = FileUtility::listPhpClassFileObjects($filePath);
        foreach ( $fileObjects as $fileObject ) {
            $classNames = $fileObject->getClassNames();
            foreach ( $classNames as $className ) {
                $suite->addTestSuite($className);
            }
        }
        $result = new \PHPUnit_Framework_TestResult();
        $listener = new SimpleListener($result);
        $result->addListener($listener);
        $suite->run($result);
        return $listener;
    }

    public function next ( ) {
        $this->_targetFilePaths->next();
    }

    public function valid ( ) {
        return $this->_targetFilePaths->valid();
    }
}
