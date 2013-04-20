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
     * @var array
     */
    private $_targetFilePaths = array();

    /**
     * Constructor
     *
     * @param array $targetFilePaths
     */
    public function __construct ( array $targetFilePaths ) {
        $this->_targetFilePaths = $targetFilePaths;
    }

    public function rewind ( ) {
        reset($this->_targetFilePaths);
    }

    public function key ( ) {
        return key($this->_targetFilePaths);
    }

    public function current ( ) {
        $suite = new \PHPUnit_Framework_TestSuite();
        $filePath = current($this->_targetFilePaths);
        require_once $filePath;

        $fileObjects = FileUtility::listPhpClassFileObjects($filePath);
        foreach ($fileObjects as $fileObject) {
            $classNames = $fileObject->getClassNames();
            foreach ($classNames as $className) {
                $suite->addTestSuite($className);
            }
        }
        $result = $suite->run();
        return $result;
    }

    public function next ( ) {
        next($this->_targetFilePaths);
    }

    public function valid ( ) {
        return !is_null($this->key());
    }
}
