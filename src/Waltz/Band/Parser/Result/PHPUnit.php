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

/**
 * PHPUnit
 *
 * @package Waltz.Band
 */
class PHPUnit
{
    /**
     * Constant key of test code
     */
    const KEY_TEST_CODE = '_testCode';

    /**
     * Constant key of method name
     */
    const KEY_METHOD_NAME = '_methodName';

    /**
     * Target file path to test
     *
     * @var string
     */
    private $_targetPath = '';

    /**
     * SetUp before class codes
     *
     * @var array
     */
    private $_setUpBeforeClassCodes = array();

    /**
     * TearDown after class codes
     *
     * @var array
     */
    private $_tearDownAfterClassCodes = array();

    /**
     * SetUp codes
     *
     * @var array
     */
    private $_setUpCodes = array();

    /**
     * TearDown codes
     *
     * @var array
     */
    private $_tearDownCodes = array();

    /**
     * Test codes
     *
     * @var array
     */
    private $_testCodes = array();

    /**
     * Set target file path to test
     *
     * @param string $targetPath
     */
    public function setTargetPath ( $targetPath )
    {
        $this->_targetPath = $targetPath;
    }

    /**
     * Add setUp before class code
     *
     * @param string $className
     * @param string $setUpCode
     */
    public function addSetUpBeforeClassCode ( $className, $setUpCode )
    {
        if ( isset($this->_setUpBeforeClassCodes[$className]) === false ) {
            $this->_setUpBeforeClassCodes[$className] = '';
        }
        $this->_setUpBeforeClassCodes[$className] .= $setUpCode . "\n";
    }

    /**
     * Add tearDown after class code
     *
     * @param string $className
     * @param string $tearDownCode
     */
    public function addTearDownAfterClassCode ( $className, $tearDownCode )
    {
        if ( isset($this->_tearDownAfterClassCodes[$className]) === false ) {
            $this->_tearDownAfterClassCodes[$className] = '';
        }
        $this->_tearDownAfterClassCodes[$className] .= $tearDownCode . "\n";
    }

    /**
     * Add setUp Code
     *
     * @param string $className
     * @param string $setUpCode
     */
    public function addSetUpCode ( $className, $setUpCode )
    {
        if ( isset($this->_setUpCodes[$className]) === false ) {
            $this->_setUpCodes[$className] = '';
        }
        $this->_setUpCodes[$className] .= $setUpCode . "\n";
    }

    /**
     * Add tearDown Code
     *
     * @param string $className
     * @param string $tearDownCode
     */
    public function addTearDownCode ( $className, $tearDownCode )
    {
        if ( isset($this->_tearDownCodes[$className]) === false ) {
            $this->_tearDownCodes[$className] = '';
        }
        $this->_tearDownCodes[$className] .= $tearDownCode . "\n";
    }

    /**
     * Add test code
     *
     * @param string $className
     * @param string $methodName
     * @param string $testCode
     * @param string $testName
     */
    public function addTestCode ( $className, $methodName, $testCode, $testName = '' )
    {
        if ( is_string($testName) === false
             || ( is_string($testName) === true && strlen($testName) === 0 ) ) {
            $testCount = $this->_countSameMethodTest($className, $methodName);
            if ( $testCount >= 1 ) {
                $testName = $methodName . '_' . strval($testCount + 1);
            } else {
                $testName = $methodName;
            }
        }
        $testCodeInfo = array(
                              self::KEY_METHOD_NAME => $methodName,
                              self::KEY_TEST_CODE => $testCode,
                             );
        $this->_testCodes[$className][$testName] = $testCodeInfo;
    }

    /**
     * Get target file path to test
     *
     * @return string Target file path to test
     */
    public function getTargetPath (  )
    {
        return $this->_targetPath;
    }

    /**
     * Get added class names
     *
     * @return array Added class names
     */
    public function getClassNames (  )
    {
        $classNames = array_keys($this->_testCodes);
        return $classNames;
    }

    /**
     * Get setUp before class code
     *
     * @param string $className
     * @return string SetUp before class code
     */
    public function getSetUpBeforeClassCode ( $className )
    {
        if ( isset($this->_setUpBeforeClassCodes[$className]) === false ) {
            $this->_setUpBeforeClassCodes[$className] = '';
        } else {
            if ( empty($this->_setUpBeforeClassCodes[$className]) === true ) {
                $this->_setUpBeforeClassCodes[$className] = '';
            }
        }
        return $this->_setUpBeforeClassCodes[$className];
    }

    /**
     * Get tearDown after class code
     *
     * @param string $className
     * @return string TearDown after class Code
     */
    public function getTearDownAfterClassCode ( $className )
    {
        if ( isset($this->_tearDownAfterClassCodes[$className]) === false ) {
            $this->_tearDownAfterClassCodes[$className] = '';
        } else {
            if ( empty($this->_tearDownAfterClassCodes[$className]) === true ) {
                $this->_tearDownAfterClassCodes[$className] = '';
            }
        }
        return $this->_tearDownAfterClassCodes[$className];
    }

    /**
     * Get setUp code
     *
     * @param string $className
     * @return string setUp code
     */
    public function getSetUpCode ( $className )
    {
        if ( isset($this->_setUpCodes[$className]) === false ) {
            $this->_setUpCodes[$className] = '';
        } else {
            if ( empty($this->_setUpCodes[$className]) === true ) {
                $this->_setUpCodes[$className] = '';
            }
        }
        if ( $this->_setUpCodes[$className] === '' && in_array($className, self::getClassNames()) === true) {
            $targetPath = $this->getTargetPath();
            list($namespace, $classNameWithoutNamespace) = \Waltz\Stagehand\ClassUtility::splitClassName($className);
            $defaultSetUpCode = '$this->_target = new ' . $classNameWithoutNamespace . '();' . "\n";
            $this->_setUpCodes[$className] = $defaultSetUpCode;
        }

        return $this->_setUpCodes[$className];
    }

    /**
     * Get tearDown code
     *
     * @param string $className
     * @return string tearDown code
     */
    public function getTearDownCode ( $className )
    {
        if ( isset($this->_tearDownCodes[$className]) === false ) {
            return '';
        }
        return $this->_tearDownCodes[$className];
    }

    /**
     * Get test codes
     *
     * @param string $className
     * @return array Test codes
     */
    public function getTestCodes ( $className )
    {
        if ( isset($this->_testCodes[$className]) === false ) {
            return array();
        }
        return $this->_testCodes[$className];
    }

    /**
     * Count tests for same method
     *
     * @param string $className
     * @param string $methodName
     * @return int Counts tests for same method
     */
    private function _countSameMethodTest ( $className, $methodName )
    {
        if ( isset($this->_testCodes[$className]) === false ) {
            return 0;
        }

        $max = 0;
        foreach ( $this->_testCodes[$className] as $testName => $testCodeInfo ) {
            if ( isset($testCodeInfo[self::KEY_METHOD_NAME]) === false ) {
                continue;
            }
            if ( $testName === $methodName ) {
                $max = 1;
                continue;
            }

            $pattern = "/{$methodName}_(\d+)/";
            if ( preg_match($pattern, $testName, $matches) === 1 ) {
                $count = intval($matches[1]);
                if ( $count > $max ) {
                    $max = $count;
                }
            }
        }
        return $max;
    }
}
