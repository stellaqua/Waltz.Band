<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Extractor\PhpDocComment;

use Waltz\Stagehand\FileUtility;
use Waltz\Stagehand\ClassUtility\ClassObject\PhpClassObject;
use Waltz\Band\Extractor\PhpDocComment\PhpDocComments;

/**
 * PhpDocCommentIterator
 *
 * @uses Iterator
 * @package Waltz.Band
 */
class PhpDocCommentIterator implements \Iterator
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
        $filePath = current($this->_targetFilePaths);
        $phpDocComments = new PhpDocComments();
        $phpDocComments->setFilePath($filePath);
        $fileObject = FileUtility::getPhpClassFileObject($filePath);
        $classNames = $fileObject->getClassNames();
        foreach ($classNames as $className) {
            $classObject = new PhpClassObject($className, '', $filePath);
            $classDocComment = $classObject->getDocComment();
            $phpDocComments->addClassDocComment($className, $classDocComment);
            $methodObjects = $classObject->listPhpMethodObjects();
            foreach ( $methodObjects as $methodObject ) {
                $methodName = $methodObject->getName();
                $methodDocComment = $methodObject->getDocComment();
                $phpDocComments->addMethodDocComment($className, $methodName, $methodDocComment);
            }
        }
        return $phpDocComments;
    }

    public function next ( ) {
        next($this->_targetFilePaths);
    }

    public function valid ( ) {
        return !is_null($this->key());
    }
}
