<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band;

use Waltz\Score\Simple as SimpleScore;
use Waltz\Band\Runner\Simple as SimpleRunner;
use Waltz\Band\Notifier\Simple as SimpleNotifier;

/**
 * Simple
 *
 * @uses BandInterface
 * @package Waltz.Band
 */
class Simple implements BandInterface
{
    /**
     * Simple score instance
     *
     * @var Waltz\Band\Score\Simple
     */
    private $_score;

    /**
     * Constructor
     *
     * @param Waltz\Score\Simple $score Simple score instance
     */
    public function __construct ( SimpleScore $score ) {
        $this->_score = $score;
    }

    /**
     * play
     */
    public function play ( ) {
        $testFilePaths = $this->_score->getTestFilePaths();
        $runner = new SimpleRunner($testFilePaths);
        $notifier = new SimpleNotifier();
        $notifier->setRunnerIterator($runner)
            ->output();
        $notifier->outputFailureResults();
    }
}
