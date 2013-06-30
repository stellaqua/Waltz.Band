<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Runner;

use Waltz\Band\Runner\RunnerInterface;
use Waltz\Band\Runner\Simple\SimpleIterator;
use Waltz\Band\Builder\BuilderInterface;

/**
 * Simple
 *
 * @uses RunnerInterface
 * @uses IteratorAggregate
 * @package Waltz.Band
 */
class Simple implements RunnerInterface, \IteratorAggregate
{
    /**
     * Target file paths
     *
     * @var array
     */
    private $_targetFilePaths = array();

    /**
     * Builder instance
     *
     * @var BuilderInterface
     */
    private $_builder = null;

    /**
     * Constructor
     *
     * @param array $targetFilePaths
     */
    public function __construct ( array $targetFilePaths = array() ) {
        $this->_targetFilePaths = $targetFilePaths;
    }

    /**
     * Set builder instance
     *
     * @param BuilderInterface $builder
     */
    public function setBuilder ( BuilderInterface $builder )
    {
        $this->_builder = $builder;
    }

    /**
     * getIterator
     *
     * @return SimpleIterator
     */
    public function getIterator ( ) {
        if ( is_null($this->_builder) === true ) {
            $target = new \ArrayIterator($this->_targetFilePaths);
        } else {
            $target = $this->_builder->getIterator();
        }
        $iterator = new SimpleIterator($target);
        return $iterator;
    }
}
