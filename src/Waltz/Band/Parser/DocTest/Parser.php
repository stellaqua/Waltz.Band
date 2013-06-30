<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Parser\DocTest;

/**
 * Parser
 *
 * @package Waltz.Band
 */
class Parser
{
    /**
     * Constant key of test code
     */
    const KEY_TEST_CODE = '_testCode';

    /**
     * Constant key of test name
     */
    const KEY_TEST_NAME = '_testName';

    /**
     * Parse DocTest code to setUp codes
     *
     * @param string $docTest DocTest code
     * @return array SetUp codes
     */
    public static function parseSetUpCodes ( $docTest )
    {
        $results = array();
        $pattern = "|\s*#setup\s*\r?\n\s*<code>\s*\r?\n(.*?\r?\n)\s*</code>|sui";
        if ( preg_match_all($pattern, $docTest, $matches, PREG_SET_ORDER) >= 1 ) {
            foreach ( $matches as $match ) {
                $result = array(self::KEY_TEST_CODE => $match[1]);
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * Parse DocTest code to tearDown codes
     *
     * @param string $docTest DocTest code
     * @return array TearDown codes
     */
    public static function parseTearDownCodes ( $docTest )
    {
        $results = array();
        $pattern = "|\s*#tearDown\s*\r?\n\s*<code>\s*\r?\n(.*?\r?\n)\s*</code>|sui";
        if ( preg_match_all($pattern, $docTest, $matches, PREG_SET_ORDER) >= 1 ) {
            foreach ( $matches as $match ) {
                $result = array(self::KEY_TEST_CODE => $match[1]);
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * Parse DocTest code to test codes
     *
     * @param string $docTest DocTest code
     * @param string $methodName Target method name
     * @return array Test codes
     */
    public static function parseTestCodes ( $docTest, $methodName )
    {
        $results = array();
        $pattern = "{";
        $pattern.= "\s*#test(\s*\r?\n|\s+(.*?)\r?\n)";
        $pattern.= "\s*<code>\s*\r?\n";
        $pattern.= "(.*?\r?\n)";
        $pattern.= "\s*</code>";
        $pattern.= "}sui";
        if ( preg_match_all($pattern, $docTest, $matches, PREG_SET_ORDER) >= 1 ) {
            foreach ( $matches as $match ) {
                $testName = $match[2];
                $testCode = $match[3];
                $testCode = self::_parseSyntaxSugar($testCode, $methodName);
                $result = array(
                                self::KEY_TEST_NAME => $testName,
                                self::KEY_TEST_CODE => $testCode,
                               );
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * Parse syntax sugar
     *
     * @param string $docTest
     * @param string $methodName
     * @return string Parsed code
     */
    private static function _parseSyntaxSugar ( $docTest, $methodName )
    {
        $syntaxes = array(
                          '/#f\(/' => '$this->_target->' . $methodName . '(',
                          '/#eq\(/' => '$this->assertEquals(',
                          '/#same\(/' => '$this->assertSame(',
                          '/#true\(/' => '$this->assertTrue(',
                          '/#false\(/' => '$this->assertFalse(',
                         );
        $patterns = array_keys($syntaxes);
        $replacements = array_values($syntaxes);
        $result = preg_replace($patterns, $replacements, $docTest);
        return $result;
    }
}
