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
     * @return SimpleIterator
     */
    public function getIterator ( ) {
        $iterator = new SimpleIterator($this->_targetFilePaths);
        return $iterator;
    }
}
