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

/**
 * PhpDocComments
 *
 * @package Waltz.Band
 */
class PhpDocComments
{
    /**
     * Constant key of class DocComment
     */
    const KEY_CLASS_DOCCOMMENT = '_classDocComment';

    /**
     * Constant key of method DocComments
     */
    const KEY_METHOD_DOCCOMMENTS = '_methodDocComments';

    /**
     * File path
     *
     * @var string
     */
    private $_filePath = '';

    /**
     * DocComments
     *
     * @var array
     */
    private $_docComments = array();

    /**
     * Set file path
     *
     * @param string $filePath
     */
    public function setFilePath ( $filePath )
    {
        $this->_filePath = $filePath;
    }

    /**
     * Add DocComment of class
     *
     * @param string $className
     * @param string $classDocComment
     */
    public function addClassDocComment ( $className, $classDocComment )
    {
        $this->_docComments[$className][self::KEY_CLASS_DOCCOMMENT] = $classDocComment;
    }

    /**
     * Add DocComment of method
     *
     * @param string $className
     * @param string $methodName
     * @param string $methodDocComment
     */
    public function addMethodDocComment ( $className, $methodName, $methodDocComment )
    {
        $this->_docComments[$className][self::KEY_METHOD_DOCCOMMENTS][$methodName] = $methodDocComment;
    }

    /**
     * Get file path
     *
     * @return string File path
     */
    public function getFilePath (  )
    {
        return $this->_filePath;
    }

    /**
     * Get added class names
     *
     * @return array Added class names
     */
    public function getClassNames (  )
    {
        $classNames = array_keys($this->_docComments);
        return $classNames;
    }

    /**
     * Get DocComment of class
     *
     * @param string $className
     * @return string DocComment
     */
    public function getClassDocComment ( $className )
    {
        if ( isset($this->_docComments[$className]) === false ) {
            return '';
        }
        $classDocComment = $this->_docComments[$className][self::KEY_CLASS_DOCCOMMENT];
        return $classDocComment;
    }

    /**
     * Get DocComments of method
     *
     * @param string $className
     * @return array DocComments of method
     */
    public function getMethodDocComments ( $className )
    {
        if ( isset($this->_docComments[$className]) === false ) {
            return array();
        }
        if ( isset($this->_docComments[$className][self::KEY_METHOD_DOCCOMMENTS]) === false ) {
            return array();
        }
        $methodDocComments = $this->_docComments[$className][self::KEY_METHOD_DOCCOMMENTS];
        return $methodDocComments;
    }
}
