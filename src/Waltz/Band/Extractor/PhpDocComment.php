<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Extractor;

use Waltz\Band\Extractor\ExtractorInterface;
use Waltz\Band\Extractor\PhpDocComment\PhpDocCommentIterator;

/**
 * PhpDocComment
 *
 * @uses ExtractorInterface
 * @uses IteratorAggregate
 * @package Waltz.Band
 */
class PhpDocComment implements ExtractorInterface, \IteratorAggregate
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

    /**
     * getIterator
     *
     * @return PhpDocCommentIterator
     */
    public function getIterator ( ) {
        $iterator = new PhpDocCommentIterator($this->_targetFilePaths);
        return $iterator;
    }
}
