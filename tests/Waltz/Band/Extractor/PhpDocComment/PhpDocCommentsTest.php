<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Extractor\PhpDocComment;

use Waltz\Band\Extractor\PhpDocComment\PhpDocComments;

/**
 * PhpDocCommentsTest
 *
 * @uses PHPUnit_Framework_TestCase
 * @package Waltz.Band
 */
class PhpDocCommentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test data of class DocComments
     *
     * @var array
     */
    private $_classDocComments;

    /**
     * Test data of method DocComments
     *
     * @var array
     */
    private $_methodDocComments;

    /**
     * PhpDocComments instance
     *
     * @var PhpDocComments
     */
    private $_phpDocComments;

    /**
     * setUp
     */
    protected function setUp (  )
    {
        $phpDocComments = new PhpDocComments();
        $filePath = '/path/to/file.php';
        $phpDocComments->setFilePath($filePath);
        $classDocComments = array(
                                  'firstClass' => 'firstClassDocComment',
                                  'secondClass' => 'secondClassDocComment',
                                 );
        $methodDocComments = array(
                                   'firstMethod' => 'firstMethodDocComment',
                                   'secondMethod' => 'secondMethodDocComment',
                                  );
        foreach ( $classDocComments as $className => $classDocComment ) {
            $phpDocComments->addClassDocComment($className, $classDocComment);
            foreach ( $methodDocComments as $methodName => $methodDocComment ) {
                $phpDocComments->addMethodDocComment($className, $methodName, $methodDocComment);
            }
        }
        $this->_classDocComments = $classDocComments;
        $this->_methodDocComments = $methodDocComments;
        $this->_phpDocComments = $phpDocComments;
    }

    /**
     * test_getFilePath
     */
    public function test_getFilePath (  )
    {
        $expected = '/path/to/file.php';
        $this->assertSame($expected, $this->_phpDocComments->getFilePath());
    }

    /**
     * test_getClassNames
     */
    public function test_getClassNames (  )
    {
        $expected = array_keys($this->_classDocComments);
        $this->assertSame($expected, $this->_phpDocComments->getClassNames());
    }

    /**
     * test_getClassNames_Without_Adding_Class_DocComment
     */
    public function test_getClassNames_Without_Adding_Class_DocComment (  )
    {
        $phpDocComments = new PhpDocComments();
        $this->assertSame(array(), $phpDocComments->getClassNames());
    }

    /**
     * test_getClassDocComment
     */
    public function test_getClassDocComment (  )
    {
        $classNames = $this->_phpDocComments->getClassNames();
        foreach ( $classNames as $className ) {
            $expected = $this->_classDocComments[$className];
            $this->assertSame($expected, $this->_phpDocComments->getClassDocComment($className));
        }
    }

    /**
     * test_getClassDocComment_For_Invalid_Classname
     */
    public function test_getClassDocComment_For_Invalid_Classname (  )
    {
        $this->assertSame('', $this->_phpDocComments->getClassDocComment('InvalidClass'));
    }

    /**
     * test_getMethodDocComments
     */
    public function test_getMethodDocComments (  )
    {
        $classNames = $this->_phpDocComments->getClassNames();
        foreach ( $classNames as $className ) {
            $methodDocComments = $this->_phpDocComments->getMethodDocComments($className);
            $this->assertSame($this->_methodDocComments, $methodDocComments);
        }
    }

    /**
     * test_getMethodDocComments_For_Invalid_Classname
     */
    public function test_getMethodDocComments_For_Invalid_Classname (  )
    {
        $this->assertSame(array(), $this->_phpDocComments->getMethodDocComments('InvalidClass'));
    }

    /**
     * test_getMethodDocComments_Without_Adding_Method_DocComment
     */
    public function test_getMethodDocComments_Without_Adding_Method_DocComment (  )
    {
        $phpDocComments = new PhpDocComments();
        $className = 'FirstClass';
        $phpDocComments->addClassDocComment($className, 'firstClassDocComment');
        $this->assertSame(array(), $phpDocComments->getMethodDocComments($className));
    }
}

