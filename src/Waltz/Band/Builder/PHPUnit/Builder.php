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

/**
 * Builder
 *
 * @package Waltz.Band
 */
class Builder
{
    /**
     * Indent string of building code
     */
    const INDENT = '    ';

    /**
     * Build test file name
     *
     * @param string $targetPath
     * @return string Test file name
     */
    public static function buildTestFileName ( $targetPath )
    {
        $pathinfo = pathinfo($targetPath);
        $dirname = $pathinfo['dirname'];
        $filename = $pathinfo['filename'];
        $extension = $pathinfo['extension'];
        $dirpath = substr(str_replace('/', '_', $dirname), 1);
        $testFileName = "{$dirpath}_{$filename}Test.{$extension}";
        return $testFileName;
    }

    /**
     * Build PHP file
     *
     * @param string $classes Classes code
     * @param string $prefix Prefix code
     * @param string $suffix Suffix code
     * @return string PHP file string
     */
    public static function buildPhpClassFile ( $classes, $prefix = '', $suffix = '' )
    {
        $values = array(
                        'prefix' => $prefix,
                        'classes' => $classes,
                        'suffix' => $suffix,
                       );
        $result = self::_buildTemplate('file', $values);
        return $result;
    }

    /**
     * Build namespace definition
     *
     * @param string $namespace
     * @return string Namespace definition
     */
    public static function buildNamespaceDefinition ( $namespace )
    {
        $result = '';
        if ( strlen($namespace) > 0 ) {
            $result = "namespace $namespace;\n";
        }
        return $result;
    }

    /**
     * Build test class name
     *
     * @param string $className
     * @return string Test class name
     */
    public static function buildTestClassName ( $className )
    {
        $result = "{$className}Test";
        return $result;
    }

    /**
     * Build test class definition
     *
     * @param array $elements
     * @return string Class definition
     */
    public static function buildTestClassDefinition ( $elements )
    {
        if ( isset($elements['className']) === false ) {
            return '';
        }
        if ( isset($elements['testCodes']) === false ) {
            return '';
        }
        list($namespace, $className) = \Waltz\Stagehand\ClassUtility::splitClassName($elements['className']);
        $values = array(
                        'namespaceDefinition' => self::buildNamespaceDefinition($namespace),
                        'propertiesDefinition' => self::_addIndent('private $_target;'),
                        'className' => self::buildTestClassName($className),
                        'testCodes' => self::_addIndent($elements['testCodes']),
                       );
        $methods = array(
                         'setUpBeforeClass' => 'public static',
                         'setUp' => 'protected',
                         'tearDown' => 'protected',
                         'tearDownAfterClass' => 'public static',
                        );
        foreach ( $methods as $methodName => $modifier) {
            if ( isset($elements[$methodName]) === true ) {
                $methodCode = $elements[$methodName];
                if ( strlen($methodCode) >= 1 ) {
                    $methodDefinition = self::buildMethodDefinition($methodName, $methodCode, $modifier);
                    $values[$methodName] = self::_addIndent($methodDefinition);
                } else {
                    $values[$methodName] = '';
                }
            }
        }
        $result = self::_buildTemplate('class', $values);
        return $result;
    }

    /**
     * Build class code
     *
     * @param string $code
     * @return string Class code
     */
    public static function buildClassCode ( $code )
    {
        $result = self::_addIndent($code);
        return $result;
    }

    /**
     * Build method definition
     *
     * @param string $methodName
     * @param string $methodCode
     * @param string $modifier
     * @return string Method definition
     */
    public static function buildMethodDefinition ( $methodName, $methodCode, $modifier = 'public' )
    {
        $values = array(
                        'modifier' => $modifier,
                        'methodName' => $methodName,
                        'methodCode' => self::buildMethodCode($methodCode),
                       );
        $result = self::_buildTemplate('method', $values);
        return $result;
    }

    /**
     * Build test method definition
     *
     * @param string $testName
     * @param string $methodName
     * @param string $methodCode
     * @param string $modifier
     * @return string Method definition
     */
    public static function buildTestMethodDefinition ( $testName, $methodName, $methodCode, $modifier = 'public' )
    {
        $methodName = "test_{$testName}";
        $methodDefinition = self::buildMethodDefinition($methodName, $methodCode, $modifier);
        return $methodDefinition;
    }

    /**
     * Build method code
     *
     * @param string $code
     * @return string Method code
     */
    public static function buildMethodCode ( $code )
    {
        $result = self::_addIndent($code);
        return $result;
    }

    /**
     * Add indent
     *
     * @param string $code
     * @return string Added code
     */
    private static function _addIndent ( $code )
    {
        $pattern = '/^(.*)$/mu';
        $replacement = self::INDENT . '$1';
        $result = preg_replace($pattern, $replacement, $code);
        return $result;
    }

    /**
     * Build template
     *
     * @param string $templateName
     * @param array $values
     * @return string Built template
     */
    private static function _buildTemplate ( $templateName, $values )
    {
        $templatePath = __DIR__ . "/templates/{$templateName}.tpl";
        if ( is_readable($templatePath) === false ) {
            return '';
        }
        $template = file_get_contents($templatePath);
        if ( is_array($values) === true && count($values) > 0 ) {
            $patterns = array_map(function ( $key, $value ) {
                                  $eol = ( strlen($value) === 0 ) ? '(\r?\n)+' : '';
                                  return "/\{\{{$key}\}\}{$eol}/";
                                  }, array_keys($values), array_values($values));
            $replacements = array_map(function ( $value ) {
                                      $pattern = '/\r?\n$/u';
                                      return preg_replace($pattern, '', $value);
                                      }, array_values($values));
            $template = preg_replace($patterns, $replacements, $template);
        }
        $result = $template;
        return $result;
    }
}
