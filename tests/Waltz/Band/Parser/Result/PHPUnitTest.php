<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Parser\Result;

use Waltz\Band\Parser\Result\PHPUnit as PHPUnitCodes;

/**
 * PHPUnitTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class PHPUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test data of setUp codes
     *
     * @var array
     */
    private $_setUpCodes;

    /**
     * Test data of tearDown codes
     *
     * @var array
     */
    private $_tearDownCodes;

    /**
     * Test data of test codes
     *
     * @var array
     */
    private $_testCodes;

    /**
     * PHPUnitCodes instance
     *
     * @var PHPUnitCodes
     */
    private $_phpunitCodes;

    /**
     * setUp
     */
    protected function setUp (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $keyMethodName = PHPUnitCodes::KEY_METHOD_NAME;
        $keyTestCode = PHPUnitCodes::KEY_TEST_CODE;
        $firstMethodTestCode = array(
                                     $keyMethodName => 'firstMethod',
                                     $keyTestCode => 'firstMethodTestCode',
                                    );
        $secondMethodTestCode = array(
                                      $keyMethodName => 'secondMethod',
                                      $keyTestCode => 'secondMethodTestCode',
                                     );
        $methodTestCodes = array(
                                 'firstMethod' => $firstMethodTestCode,
                                 'secondMethod' => $secondMethodTestCode,
                                );
        $testCodes = array(
                           'FirstClass' => $methodTestCodes,
                           'SecondClass' => $methodTestCodes,
                          );
        $setUpCodes1 = array(
                             'FirstClass' => "firstClassSetUpCode1\nrequire_once '':\n\$this->_target = new FirstClass();\n",
                             'SecondClass' => "secondClassSetUpCode1\nrequire_once '';\n\$this->_target = new SecondClass();\n",
                            );
        $setUpCodes2 = array(
                             'FirstClass' => 'firstClassSetUpCode2',
                             'SecondClass' => 'secondClassSetUpCode2',
                            );
        $tearDownCodes1 = array(
                                'FirstClass' => 'firstClassTearDownCode1',
                                'SecondClass' => 'secondClassTearDownCode1',
                               );
        $tearDownCodes2 = array(
                                'FirstClass' => 'firstClassTearDownCode2',
                                'SecondClass' => 'secondClassTearDownCode2',
                               );
        foreach ( $testCodes as $className => $methodTestCodes ) {
            foreach ( $methodTestCodes as $methodName => $testCodeInfo ) {
                $testCode = $testCodeInfo[$keyTestCode];
                $phpunitCodes->addTestCode($className, $methodName, $testCode);
            }
        }
        foreach ( $setUpCodes1 as $className => $setUpCode ) {
            $phpunitCodes->addSetUpCode($className, $setUpCode);
            $this->_setUpCodes[$className][] = $setUpCode;
        }
        foreach ( $setUpCodes2 as $className => $setUpCode ) {
            $phpunitCodes->addSetUpCode($className, $setUpCode);
            $this->_setUpCodes[$className][] = $setUpCode;
        }
        foreach ( $tearDownCodes1 as $className => $tearDownCode ) {
            $phpunitCodes->addTearDownCode($className, $tearDownCode);
            $this->_tearDownCodes[$className][] = $tearDownCode;
        }
        foreach ( $tearDownCodes2 as $className => $tearDownCode ) {
            $phpunitCodes->addTearDownCode($className, $tearDownCode);
            $this->_tearDownCodes[$className][] = $tearDownCode;
        }
        $this->_testCodes = $testCodes;
        $this->_phpunitCodes = $phpunitCodes;
    }

    /**
     * test_getClassNames
     */
    public function test_getClassNames (  )
    {
        $expected = array_keys($this->_testCodes);
        $this->assertSame($expected, $this->_phpunitCodes->getClassNames());
    }

    /**
     * test_getClassNames_Without_Adding_Test_Code
     */
    public function test_getClassNames_Without_Adding_Test_Code (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $this->assertSame(array(), $phpunitCodes->getClassNames());
    }

    /**
     * test_getSetUpCode
     */
    public function test_getSetUpCode (  )
    {
        $classNames = $this->_phpunitCodes->getClassNames();
        foreach ( $classNames as $className ) {
            $expected = implode("\n", $this->_setUpCodes[$className]) . "\n";
            $this->assertSame($expected, $this->_phpunitCodes->getSetUpCode($className));
        }
    }

    /**
     * test_getSetUpCode_For_Invalid_Classname
     */
    public function test_getSetUpCode_For_Invalid_Classname (  )
    {
        $this->assertSame('', $this->_phpunitCodes->getSetUpCode('InvalidClass'));
    }

    /**
     * test_getTearDownCode
     */
    public function test_getTearDownCode (  )
    {
        $classNames = $this->_phpunitCodes->getClassNames();
        foreach ( $classNames as $className ) {
            $expected = implode("\n", $this->_tearDownCodes[$className]) . "\n";
            $this->assertSame($expected, $this->_phpunitCodes->getTearDownCode($className));
        }
    }

    /**
     * test_getTearDownCode_For_Invalid_Classname
     */
    public function test_getTearDownCode_For_Invalid_Classname (  )
    {
        $this->assertSame('', $this->_phpunitCodes->getTearDownCode('InvalidClass'));
    }

    /**
     * test_getTestCodes
     */
    public function test_getTestCodes (  )
    {
        $classNames = $this->_phpunitCodes->getClassNames();
        foreach ( $classNames as $className ) {
            $expected = $this->_testCodes[$className];
            $this->assertSame($expected, $this->_phpunitCodes->getTestCodes($className));
        }
    }

    /**
     * test_getTestCodes_For_Invalid_Classname
     */
    public function test_getTestCodes_For_Invalid_Classname (  )
    {
        $this->assertSame(array(), $this->_phpunitCodes->getTestCodes('InvalidClass'));
    }

    /**
     * test_getTestCodes_By_Setting_Test_Name
     */
    public function test_getTestCodes_By_Setting_Test_Name (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $className = 'FirstClass';
        $methodName = 'firstMethod';
        $testCode = 'firstMethodTestCode';
        $testName = 'firstMethodTestName';
        $phpunitCodes->addTestCode($className, $methodName, $testCode, $testName);
        $expectedTestCode = array(
                                  PHPUnitCodes::KEY_METHOD_NAME => $methodName,
                                  PHPUnitCodes::KEY_TEST_CODE => $testCode,
                                 );
        $expected = array($testName => $expectedTestCode);
        $this->assertSame($expected, $phpunitCodes->getTestCodes($className));
    }

    /**
     * test_getTestCodes_By_Adding_Test_Codes_For_Same_Method
     */
    public function test_getTestCodes_By_Adding_Test_Codes_For_Same_Method (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $className = 'FirstClass';
        $methodName = 'firstMethod';
        $testCode1 = 'firstMethodTestCode';
        $testCode2 = 'firstMethodTestCode';
        $phpunitCodes->addTestCode($className, $methodName, $testCode1);
        $phpunitCodes->addTestCode($className, $methodName, $testCode2);

        $testName1 = 'firstMethod';
        $testName2 = 'firstMethod_2';
        $expectedTestCode1 = array(
                                   PHPUnitCodes::KEY_METHOD_NAME => $methodName,
                                   PHPUnitCodes::KEY_TEST_CODE => $testCode1,
                                  );
        $expectedTestCode2 = array(
                                   PHPUnitCodes::KEY_METHOD_NAME => $methodName,
                                   PHPUnitCodes::KEY_TEST_CODE => $testCode2,
                                  );
        $expected = array(
                          $testName1 => $expectedTestCode1,
                          $testName2 => $expectedTestCode2,
                         );
        $this->assertSame($expected, $phpunitCodes->getTestCodes($className));
    }

    /**
     * test_getTargetPath
     */
    public function test_getTargetPath (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $targetPath = '/path/to/target.php';
        $className = 'FirstClass';
        $methodName = 'firstMethod';
        $testCode = 'firstMethodTestCode';
        $phpunitCodes->setTargetPath($targetPath);
        $phpunitCodes->addTestCode($className, $methodName, $testCode);
        $this->assertSame($targetPath, $phpunitCodes->getTargetPath());
    }

    public function test_getSetUpBeforeClassCodeAndTearDownAfterClassCode (  )
    {
        $phpunitCodes = new PHPUnitCodes();
        $targetPath = '/path/to/target.php';
        $className = 'FirstClass';
        $methodName = 'firstMethod';
        $testCode = 'firstMethodTestCode';
        $phpunitCodes->setTargetPath($targetPath);
        $phpunitCodes->addTestCode($className, $methodName, $testCode);
        $this->assertSame('', $phpunitCodes->getSetUpBeforeClassCode($className));
        $this->assertSame('', $phpunitCodes->getTearDownAfterClassCode($className));
    }
}
