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

use Waltz\Score\Simple as SimpleScore;
use Waltz\Band\Runner\Simple as SimpleRunner;
use Waltz\Band\Runner\Simple\SimpleListener;

/**
 * SimpleTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class SimpleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * setUp
     */
    protected function setUp ( ) {
        $this->_dataDir = __DIR__ . '/data/SimpleTest';
    }

    /**
     * test_Iteration
     */
    public function test_Iteration ( ) {
        $targetPath = $this->_dataDir . '/SimplePhpTestClass.php';
        $score = new SimpleScore($targetPath);
        $runner = new SimpleRunner($score->getTestFilePaths());
        foreach ($runner as $listener) {
            $this->assertInstanceOf('Waltz\Band\Runner\Simple\SimpleListener', $listener);
            foreach ( $listener as $className => $results ) {
                $this->assertSame('', $className);
                foreach ( $results as $methodName => $result ) {
                    $this->assertSame('', $methodName);
                    $this->assertSame(SimpleListener::RESULT_OK, $result);
                }
            }
        }
    }
}
