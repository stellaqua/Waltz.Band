<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Builder\PHPUnit;

use Waltz\Band\Builder\PHPUnit\Builder as PHPUnitBuilder;

/**
 * BuilderTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test data directory
     *
     * @var string
     */
    private $_dataDir = '';

    /**
     * Indent string
     *
     * @var string
     */
    private $_indent = '    ';

    /**
     * setUp
     */
    protected function setUp ( ) {
        $this->_dataDir = __DIR__ . '/data/BuilderTest';
    }

    /**
     * test_buildTestFileName
     */
    public function test_buildTestFileName (  )
    {
        $expected = 'path_to_TargetClassTest.php';
        $targetPath = '/path/to/TargetClass.php';
        $this->assertSame($expected, PHPUnitBuilder::buildTestFileName($targetPath));
    }

    /**
     * test_buildMethodCode
     */
    public function test_buildMethodCode (  )
    {
        $expected = $this->_indent . "line1\n";
        $expected.= $this->_indent . "line2\n";
        $code = "line1\nline2\n";
        $this->assertSame($expected, PHPUnitBuilder::buildMethodCode($code));
    }

    /**
     * test_buildTestMethodDefinition
     */
    public function test_buildTestMethodDefinition (  )
    {
        $testName = 'TestName';
        $methodName = 'method';
        $methodCode = "line1\nline2\n";
        $expected = "public function test_TestName (  )\n";
        $expected.= "{\n";
        $expected.= PHPUnitBuilder::buildMethodCode($methodCode);
        $expected.= "}\n";
        $this->assertSame($expected, PHPUnitBuilder::buildTestMethodDefinition($testName, $methodName, $methodCode, 'public'));
    }

    /**
     * test_buildTestClassDefinition
     */
    public function test_buildTestClassDefinition (  )
    {
        $expected = include $this->_dataDir . '/FirstClassTest.php';
        $this->assertSame($expected, $this->_buildTestClassDefinition());
    }

    /**
     * test_buildPhpClassFile
     */
    public function test_buildPhpClassFile (  )
    {
        $expected = "<?php\n" . (include $this->_dataDir . '/FirstClassTest.php') . "\n";
        $classes = $this->_buildTestClassDefinition();
        $this->assertSame($expected, PHPUnitBuilder::buildPhpClassFile($classes));
    }

    /**
     * Build test class definition
     *
     * @return string Class definition
     */
    private function _buildTestClassDefinition (  )
    {
        $className = 'Waltz\Band\Builder\PHPUnit\BuilderTest\FirstClass';
        $setUpBeforeClassCode = '';
        $setUpCode = '// Set up code';
        $testName = 'firstMethod';
        $methodName = 'firstMethod';
        $methodCode = '// Test method code';
        $testMethodsCode = PHPUnitBuilder::buildTestMethodDefinition($testName, $methodName, $methodCode, 'public');
        $tearDownCode = '';
        $tearDownAfterClassCode = '';
        $classElements = array(
                               'className' => $className,
                               'setUpBeforeClass' => $setUpBeforeClassCode,
                               'setUp' => $setUpCode,
                               'testCodes' => $testMethodsCode,
                               'tearDown' => $tearDownCode,
                               'tearDownAfterClass' => $tearDownAfterClassCode,
                              );
        $result = PHPUnitBuilder::buildTestClassDefinition($classElements);
        return $result;
    }
}
