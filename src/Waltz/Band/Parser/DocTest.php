<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Parser;

use Waltz\Band\Parser\ParserInterface;
use Waltz\Band\Parser\DocTest\DocTestIterator;
use Waltz\Band\Extractor\PhpDocComment;

/**
 * DocTest
 *
 * @uses ParserInterface
 * @uses IteratorAggregate
 * @package Waltz.Band
 */
class DocTest implements ParserInterface, \IteratorAggregate
{
    /**
     * Extractor instance
     *
     * @var PhpDocComment
     */
    private $_extractor;

    /**
     * Set extractor instance
     *
     * @param PhpDocComment $extractor
     */
    public function setExtractor ( PhpDocComment $extractor )
    {
        $this->_extractor = $extractor;
    }

    /**
     * getIterator
     *
     * @return DocTestIterator
     */
    public function getIterator (  )
    {
        $iterator = new DocTestIterator($this->_extractor);
        return $iterator;
    }
}
