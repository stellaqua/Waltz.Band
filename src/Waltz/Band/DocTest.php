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

use Waltz\Score\DocTest as DocTestScore;
use Waltz\Band\Extractor\PhpDocComment as PhpDocCommentExtractor;
use Waltz\Band\Parser\DocTest as DocTestParser;
use Waltz\Band\Builder\PHPUnit as PHPUnitBuilder;
use Waltz\Band\Runner\Simple as SimpleRunner;
use Waltz\Band\Notifier\Simple as SimpleNotifier;

/**
 * DocTest
 *
 * @uses BandInterface
 * @package Waltz.Band
 */
class DocTest implements BandInterface
{
    /**
     * DocTest score instance
     *
     * @var Waltz\Band\Score\DocTest
     */
    private $_score;

    /**
     * Constructor
     *
     * @param Waltz\Score\DocTest $score DocTest score instance
     */
    public function __construct ( DocTestScore $score ) {
        $this->_score = $score;
    }

    /**
     * play
     */
    public function play ( ) {
        $targetFilePaths = $this->_score->getTargetFilePaths();

        // Extract DocComments from target files
        $extractor = new PhpDocCommentExtractor($targetFilePaths);

        // Parse DocComments for DocTest
        $parser = new DocTestParser();
        $parser->setExtractor($extractor);

        // Build PHPUnit test files
        $builder = new PHPUnitBuilder();
        $builder->setParser($parser);
        $testFilePath = $this->_score->getTestFilesPath();
        $builder->setOutputPath($testFilePath);

        // Run PHPUnit test files
        $runner = new SimpleRunner();
        $runner->setBuilder($builder);

        // Notifier results of running tests
        $notifier = new SimpleNotifier();
        $notifier->setRunner($runner)
            ->output()
            ->outputFailureResults()
            ;
    }
}
