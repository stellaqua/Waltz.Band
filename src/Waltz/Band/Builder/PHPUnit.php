<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Builder;

use Waltz\Band\Builder\BuilderInterface;
use Waltz\Band\Builder\PHPUnit\PHPUnitIterator;
use Waltz\Band\Parser\DocTest as DocTestParser;

/**
 * PHPUnit
 *
 * @uses BuilderInterface
 * @uses IteratorAggregate
 * @package Waltz.Band
 */
class PHPUnit implements BuilderInterface, \IteratorAggregate
{
    /**
     * Parser instance
     *
     * @var DocTestParser
     */
    private $_parser;

    /**
     * Output path
     *
     * @var string
     */
    private $_outputPath = '';

    /**
     * Set parser instance
     *
     * @param DocTestParser $parser
     */
    public function setParser ( DocTestParser $parser )
    {
        $this->_parser = $parser;
    }

    /**
     * Set output path
     *
     * @param string $outputPath
     */
    public function setOutputPath ( $outputPath )
    {
        $this->_outputPath = $outputPath;
    }

    /**
     * getIterator
     *
     * @return PHPUnitIterator
     */
    public function getIterator (  )
    {
        $iterator = new PHPUnitIterator($this->_parser, $this->_outputPath);
        return $iterator;
    }
}
