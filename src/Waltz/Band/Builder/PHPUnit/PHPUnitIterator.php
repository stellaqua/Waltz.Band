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

use Waltz\Band\Parser\DocTest as DocTestParser;
use Waltz\Band\Parser\Result\PHPUnit as PHPUnitCodes;
use Waltz\Band\Builder\PHPUnit\Builder as PHPUnitBuilder;

/**
 * PHPUnitIterator
 *
 * @uses Iterator
 * @package Waltz.Band
 */
class PHPUnitIterator implements \Iterator
{
    /**
     * Parser iterator
     *
     * @var DocTestParser
     */
    private $_parserIterator;

    /**
     * Output path
     *
     * @var string
     */
    private $_outputPath = '';

    /**
     * Constructor
     *
     * @param DocTestParser $parserIterator
     * @param string $outputPath
     */
    public function __construct ( DocTestParser $parserIterator, $outputPath ) {
        $this->_parserIterator = $parserIterator->getIterator();
        $this->_outputPath = $outputPath;
    }

    public function rewind ( ) {
        $this->_parserIterator->rewind();
    }

    public function key ( ) {
        return $this->_parserIterator->key();
    }

    public function current ( ) {
        $classes = '';
        $phpunitCodes = $this->_parserIterator->current();
        $targetPath = $phpunitCodes->getTargetPath();
        $testFileName = PHPUnitBuilder::buildTestFileName($targetPath);
        $builtFilePath = $this->_outputPath . '/' . $testFileName;
        $classNames = $phpunitCodes->getClassNames();
        foreach ( $classNames as $className ) {
            $setUpBeforeClassCode = $phpunitCodes->getSetUpBeforeClassCode($className);
            $setUpCode = $phpunitCodes->getSetUpCode($className);
            $testCodes = $phpunitCodes->getTestCodes($className);
            $testMethodsCode = '';
            foreach ( $testCodes as $testName => $testInfo ) {
                $methodName = $testInfo[PHPUnitCodes::KEY_METHOD_NAME];
                $testCode = $testInfo[PHPUnitCodes::KEY_TEST_CODE];
                $methodDefinition = PHPUnitBuilder::buildTestMethodDefinition($testName, $methodName, $testCode, 'public');
                $testMethodsCode .= $methodDefinition . "\n";
            }
            $tearDownCode = $phpunitCodes->getTearDownCode($className);
            $tearDownAfterClassCode = $phpunitCodes->getTearDownAfterClassCode($className);

            $classElements = array(
                                   'className' => $className,
                                   'setUpBeforeClass' => $setUpBeforeClassCode,
                                   'setUp' => $setUpCode,
                                   'testCodes' => $testMethodsCode,
                                   'tearDown' => $tearDownCode,
                                   'tearDownAfterClass' => $tearDownAfterClassCode,
                                  );
            $classDefinition = PHPUnitBuilder::buildTestClassDefinition($classElements);
            $classes .= $classDefinition;
        }
        $testFileContent = PHPUnitBuilder::buildPhpClassFile($classes);
        file_put_contents($builtFilePath, $testFileContent);
        return $builtFilePath;
    }

    public function next ( ) {
        $this->_parserIterator->next();
    }

    public function valid ( ) {
        return !is_null($this->key());
    }
}
