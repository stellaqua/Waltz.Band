<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Runner\Simple;

/**
 * SimpleListener
 *
 * @uses PHPUnit_Framework_TestListener
 * @package Waltz.Band
 */
class SimpleListener implements \PHPUnit_Framework_TestListener
{
    const RESULT_OK              = 'Ok';
    const RESULT_ERROR           = 'Error';
    const RESULT_FAILURE         = 'Failure';
    const RESULT_INCOMPLETE_TEST = 'IncompleteTest';
    const RESULT_SKIPPED_TEST    = 'SkippedTest';

    /**
     * PHPUnit test result instance
     *
     * @var PHPUnit_Framework_TestResult
     */
    private $_resultInstance;

    /**
     * Test results
     *
     * @var array
     */
    private $_results = array();

    /**
     * Testing class name
     *
     * @var string
     */
    private $_testingClassName = '';

    /**
     * Constructor
     *
     * @param \PHPUnit_Framework_TestResult $result
     */
    public function __construct ( \PHPUnit_Framework_TestResult $result )
    {
        $this->_resultInstance = $result;
    }

    /**
     * Implement of addError
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception $e
     * @param float $time
     */
    public function addError ( \PHPUnit_Framework_Test $test, \Exception $e, $time )
    {
        $className = $this->_testingClassName;
        $methodName = $test->getName();
        $this->_results[$this->_testingClassName][$methodName] = self::RESULT_ERROR;
    }

    /**
     * Implement of addFailure
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \PHPUnit_Framework_AssertionFailedError $e
     * @param float $time
     */
    public function addFailure ( \PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time )
    {
        $className = $this->_testingClassName;
        $methodName = $test->getName();
        $this->_results[$this->_testingClassName][$methodName] = self::RESULT_FAILURE;
    }

    /**
     * Implement of addIncompleteTest
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception $e
     * @param float $time
     */
    public function addIncompleteTest ( \PHPUnit_Framework_Test $test, \Exception $e, $time )
    {
        $className = $this->_testingClassName;
        $methodName = $test->getName();
        $this->_results[$this->_testingClassName][$methodName] = self::RESULT_INCOMPLETE_TEST;
    }

    /**
     * Implement of addSkippedTest
     *
     * @param \PHPUnit_Framework_Test $test
     * @param \Exception $e
     * @param float $time
     */
    public function addSkippedTest ( \PHPUnit_Framework_Test $test, \Exception $e, $time )
    {
        $className = $this->_testingClassName;
        $methodName = $test->getName();
        $this->_results[$this->_testingClassName][$methodName] = self::RESULT_SKIPPED_TEST;
    }

    /**
     * Implement of startTestSuite
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite ( \PHPUnit_Framework_TestSuite $suite )
    {
        $this->_testingClassName = $suite->getName();
    }

    /**
     * Implement of endTestSuite
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite ( \PHPUnit_Framework_TestSuite $suite )
    {
    }

    /**
     * Implement of startTest
     *
     * @param \PHPUnit_Framework_Test $test
     */
    public function startTest ( \PHPUnit_Framework_Test $test )
    {
        $className = $this->_testingClassName;
        $methodName = $test->getName();
        $this->_results[$this->_testingClassName][$methodName] = self::RESULT_OK;
    }

    /**
     * Implement of endTest
     *
     * @param \PHPUnit_Framework_Test $test
     * @param float $time
     */
    public function endTest ( \PHPUnit_Framework_Test $test, $time )
    {
    }

    /**
     * Get PHPUnit test result instance
     *
     * @return \PHPUnit_Framework_TestResult PHPUnit test result instance
     */
    public function getResultInstance (  )
    {
        $resultInstance = $this->_resultInstance;
        return $resultInstance;
    }

    /**
     * Get test results
     *
     * @param string $type Result type defined by class constant
     * @return array Test results
     */
    public function getResults ( $type = '' )
    {
        if ( isset($this->_results[$type]) ) {
            $results = $this->_results[$type];
        } else {
            $results = $this->_results;
        }
        return $results;
    }

    /**
     * Get test results count
     *
     * @param string $type Result type defined by class constant
     * @return int Test results count
     */
    public function getResultsCount ( $type = '' )
    {
        $count = 0;
        foreach ( $this->_results as $key => $result ) {
            if ( $type === '' || $key === $type ) {
                $count += count($result);
            }
        }
        return $count;
    }
}
