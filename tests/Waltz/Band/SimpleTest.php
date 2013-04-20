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

/**
 * SimpleTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class SimpleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test data directory
     *
     * @var string
     */
    private $_dataDir = '';

    /**
     * setUp
     */
    protected function setUp ( ) {
        $this->_dataDir = __DIR__ . '/data/SimpleTest';
    }

    /**
     * test_play_SimpleTest
     */
    public function test_play_SimpleTest ( ) {
        $targetPath = $this->_dataDir;
        $score = new \Waltz\Score\Simple($targetPath);
        $band = new \Waltz\Band\Simple($score);
        $pattern = '/\(\)/';
        ob_start();
        $band->play();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertRegExp($pattern, $output);
    }
}
