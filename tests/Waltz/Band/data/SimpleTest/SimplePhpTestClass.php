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
 * SimplePhpTestClass
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class SimplePhpTestClass extends \PHPUnit_Framework_TestCase
{
    public function test_AlwaysOk ( ) {
        $this->assertTrue(true);
    }
}
