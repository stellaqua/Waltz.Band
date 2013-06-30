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

use Waltz\Band\Parser\DocTest\Parser as DocTestParser;

/**
 * DocTestTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class DocTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test_parseSetUpCodes
     */
    public function test_parseSetUpCodes (  )
    {
        $docTest = "#setup\n";
        $docTest.= "<code>\n";
        $docTest.= "//setUp code\n";
        $docTest.= "</code>\n";
        $expectedCode = array(DocTestParser::KEY_TEST_CODE => "//setUp code\n");
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseSetUpCodes($docTest));

        $docTest = " #setUp \n";
        $docTest.= " <CODE> \n";
        $docTest.= "//setUp code\n";
        $docTest.= " </CODE> \n";
        $expectedCode = array(DocTestParser::KEY_TEST_CODE => "//setUp code\n");
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseSetUpCodes($docTest));
    }

    /**
     * test_parseTearDownCodes
     */
    public function test_parseTearDownCodes (  )
    {
        $docTest = "#teardown\n";
        $docTest.= "<code>\n";
        $docTest.= "//tearDown code\n";
        $docTest.= "</code>\n";
        $expectedCode = array(DocTestParser::KEY_TEST_CODE => "//tearDown code\n");
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTearDownCodes($docTest));

        $docTest = "#tearDown \n";
        $docTest.= "<CODE> \n";
        $docTest.= "//tearDown code\n";
        $docTest.= "</CODE> \n";
        $expectedCode = array(DocTestParser::KEY_TEST_CODE => "//tearDown code\n");
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTearDownCodes($docTest));
    }

    /**
     * test_parseTestCodes
     */
    public function test_parseTestCodes (  )
    {
        $methodName = 'firstMedhod';
        $docTest = "#test\n";
        $docTest.= "<code>\n";
        $docTest.= "//test code\n";
        $docTest.= "</code>\n";
        $expectedCode = array(
                              DocTestParser::KEY_TEST_NAME => '',
                              DocTestParser::KEY_TEST_CODE => "//test code\n",
                             );
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));

        $docTest = " #Test \n";
        $docTest.= " <CODE> \n";
        $docTest.= "//test code\n";
        $docTest.= " </CODE> \n";
        $expectedCode = array(
                              DocTestParser::KEY_TEST_NAME => '',
                              DocTestParser::KEY_TEST_CODE => "//test code\n",
                             );
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));
    }

    /**
     * test_parseTestCodes_Some_Code_Blocks
     */
    public function test_parseTestCodes_Some_Code_Blocks (  )
    {
        $methodName = 'firstMedhod';
        $docTest = "#test\n";
        $docTest.= "<code>\n";
        $docTest.= "//test code1\n";
        $docTest.= "</code>\n";
        $docTest.= "\n";
        $docTest.= "#test\n";
        $docTest.= "<code>\n";
        $docTest.= "//test code2\n";
        $docTest.= "</code>\n";
        $expectedCode1 = array(
                               DocTestParser::KEY_TEST_NAME => '',
                               DocTestParser::KEY_TEST_CODE => "//test code1\n",
                              );
        $expectedCode2 = array(
                               DocTestParser::KEY_TEST_NAME => '',
                               DocTestParser::KEY_TEST_CODE => "//test code2\n",
                              );
        $expected = array($expectedCode1, $expectedCode2);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));
    }

    /**
     * test_parseTestCodes_With_Testname
     */
    public function test_parseTestCodes_With_Testname (  )
    {
        $methodName = 'firstMedhod';
        $docTest = "#test testname\n";
        $docTest.= "<code>\n";
        $docTest.= "//test code\n";
        $docTest.= "</code>\n";
        $expectedCode = array(
                              DocTestParser::KEY_TEST_NAME => 'testname',
                              DocTestParser::KEY_TEST_CODE => "//test code\n",
                             );
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));

        $docTest = " #Test  日本語テスト名\n";
        $docTest.= " <CODE> \n";
        $docTest.= "//test code\n";
        $docTest.= " </CODE> \n";
        $expectedCode = array(
                              DocTestParser::KEY_TEST_NAME => '日本語テスト名',
                              DocTestParser::KEY_TEST_CODE => "//test code\n",
                             );
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));
    }

    /**
     * test_parseTestCodes_With_Syntax_Sugar
     */
    public function test_parseTestCodes_With_Syntax_Sugar (  )
    {
        $methodName = 'firstMedhod';
        $docTest = "#test testname\n";
        $docTest.= "<code>\n";
        $docTest.= "\$expected = 'Hello';\n";
        $docTest.= "#eq(\$expected, #f('Hello'));\n";
        $docTest.= "#same(\$expected, #f('Hello'));\n";
        $docTest.= "#true(#f(true));\n";
        $docTest.= "#false(#f(false));\n";
        $docTest.= "</code>\n";
        $testCode = "\$expected = 'Hello';\n";
        $testCode.= "\$this->assertEquals(\$expected, \$this->_target->firstMedhod('Hello'));\n";
        $testCode.= "\$this->assertSame(\$expected, \$this->_target->firstMedhod('Hello'));\n";
        $testCode.= "\$this->assertTrue(\$this->_target->firstMedhod(true));\n";
        $testCode.= "\$this->assertFalse(\$this->_target->firstMedhod(false));\n";
        $expectedCode = array(
                              DocTestParser::KEY_TEST_NAME => 'testname',
                              DocTestParser::KEY_TEST_CODE => $testCode,
                             );
        $expected = array($expectedCode);
        $this->assertSame($expected, DocTestParser::parseTestCodes($docTest, $methodName));
    }
}
