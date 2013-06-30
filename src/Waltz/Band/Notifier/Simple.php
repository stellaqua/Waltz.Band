<?php
/**
 * This file is part of the Waltz.Band package
 *
 * (c) Tomoki Kobayashi <tom@stellaqua.com>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace Waltz\Band\Notifier;

use Waltz\Band\Notifier\NotifierInterface;
use Waltz\Band\Runner\RunnerInterface;
use Waltz\Band\Runner\Simple\SimpleListener;
use Waltz\Stagehand\CuiUtility;

class Simple implements NotifierInterface
{
    /**
     * Runner instance
     *
     * @var Waltz\Band\Runner\RunnerInterface
     */
    private $_runner;

    /**
     * Set runner
     *
     * @param RunnerInterface $runner Runner instance
     * @return Simple Self instance
     */
    public function setRunner ( RunnerInterface $runner ) {
        $this->_runner = $runner;
        return $this;
    }

    /**
     * Output results
     *
     * @return Simple Self instance
     */
    public function output ( ) {
        $cuiUtility = new CuiUtility();
        $beginningBlock = $this->_getBeginningBlock();
        $delimiterBlock = $this->_getDelimiterBlock();
        $finishingBlock = $this->_getFinishingBlock();
        $okBlock = $this->_getOkBlock();
        $ngBlock = $this->_getNgBlock();
        $cuiUtility->setCanvas(count($beginningBlock))
            ->drawByBlock($beginningBlock);
        $totalCount = 0;
        $this->_runner->getIterator()->rewind();
        foreach ( $this->_runner as $key => $listener ) {
            $totalCount += $listener->getResultsCount();
        }
        $drawCount = 0;
        $this->_runner->getIterator()->rewind();
        foreach ( $this->_runner as $key => $listener ) {
            foreach ( $listener->getResults() as $className => $results ) {
                foreach ( $results as $methodName => $result ) {
                    if ( $result === SimpleListener::RESULT_OK ) {
                        $cuiUtility->drawByBlock($okBlock);
                    } else {
                        $cuiUtility->drawByBlock($ngBlock);
                    }
                    $drawCount++;
                    if ( $drawCount % 3 === 0 && $drawCount < $totalCount ) {
                        $cuiUtility->drawByBlock($delimiterBlock);
                        $canvasSize = $cuiUtility->getCanvasSize();
                        $windowSize = CuiUtility::getWindowSize();
                        if ( $canvasSize[0] + 18 >= $windowSize[0] ) {
                            $cuiUtility->finishDrawingByBlock()
                                ->setCanvas(count($delimiterBlock))
                                ->drawByBlock($delimiterBlock);
                        }
                    }
                }
            }
        }
        $cuiUtility->drawByBlock($finishingBlock)
            ->finishDrawingByBlock();
        return $this;
    }

    /**
     * Output failure results
     *
     * @return Simple Self instance
     */
    public function outputFailureResults ( ) {
        $this->_runner->getIterator()->rewind();
        foreach ( $this->_runner as $key => $listener ) {
            $isOk = true;
            foreach ( $listener->getResults() as $className => $results ) {
                foreach ( $results as $methodName => $result ) {
                    if ( $result !== SimpleListener::RESULT_OK ) {
                        $isOk = false;
                    }
                }
            }
            if ( $isOk === false ) {
                $printer = new \PHPUnit_TextUI_ResultPrinter(null, true, true);
                $printer->printResult($listener->getResultInstance());
            }
        }
        return $this;
    }

    /**
     * Get beginning block
     *
     * @return array Beginning block
     */
    private function _getBeginningBlock ( ) {
        return array(
                     '   |\\',
                     '|--|/----',
                     '|--|---3-',
                     '|-/|.----',
                     '|(-|-)-4-',
                     '|-`|\'----',
                     '  \\|',
                    );
    }

    /**
     * Get finishing block
     *
     * @return array Finishing block
     */
    private function _getFinishingBlock ( ) {
        return array(
                     '',
                     '-||',
                     '-||',
                     '-||',
                     '-||',
                     '-||',
                     '',
                    );
    }

    /**
     * Get ok block
     *
     * @return array Ok block
     */
    private function _getOkBlock ( ) {
        return array(
                     '',
                     '-----',
                     '--<tc:green>|\\</tc>-',
                     '--<tc:green>|\'</tc>-',
                     '-<tc:green>()</tc>--',
                     '-----',
                     '',
                    );
    }

    /**
     * Get ng block
     *
     * @return array Ng block
     */
    private function _getNgBlock ( ) {
        return array(
                     '',
                     '-----',
                     '--<tc:red>\\</tc>--',
                     '--<tc:red><</tc>--',
                     '--<tc:red>(</tc>--',
                     '-----',
                     '',
                    );
    }

    /**
     * Get delimiter block
     *
     * @return array Delimiter block
     */
    private function _getDelimiterBlock ( ) {
        return array(
                     '',
                     '-|-',
                     '-|-',
                     '-|-',
                     '-|-',
                     '-|-',
                     '',
                    );
    }
}
