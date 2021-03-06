<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: PHPUnit                                                        |
// +------------------------------------------------------------------------+
// | Copyright (c) 2002-2004 Sebastian Bergmann <sb@sebastian-bergmann.de>. |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//
// $Id: ComparisonFailure.php,v 1.8 2004/01/04 10:25:10 sebastian Exp $
//

require_once 'PHPUnit/Framework/AssertionFailedError.php';

/**
 * Thrown when an assertion for string equality failed.
 *
 * @package phpunit.framework
 * @author  Sebastian Bergmann <sb@sebastian-bergmann.de>
 */
class PHPUnit_Framework_ComparisonFailure extends PHPUnit_Framework_AssertionFailedError {
    // {{{ Members

    /**
    * @var    string
    * @access private
    */
    private $expected = '';

    /**
    * @var    string
    * @access private
    */
    private $actual = '';

    // }}}
    // {{{ public function __construct($expected, $actual, $message = '')

    /**
    * Constructs a comparison failure.
    *
    * @param           string $expected
    * @param           string $actual
    * @param  optional string $message
    * @access public
    */
    public function __construct($expected, $actual, $message = '') {
        parent::__construct($message);

        $this->expected = $expected;
        $this->actual   = $actual;
    }

    // }}}
    // {{{ public function toString()

    /**
    * Returns "..." in place of common prefix and "..." in
    * place of common suffix between expected and actual.
    *
    * @return string
    * @access public
    */
    public function toString() {
        if ($this->expected == null && $this->actual == null) {
            return PHPUnit_Framework_Assert::format(
                $this->expected,
                $this->actual,
                parent::getMessage()
            );
        }

        $end = min(strlen($this->expected), strlen($this->actual));
        $i   = 0;
        $j   = strlen($this->expected) - 1;
        $k   = strlen($this->actual)   - 1;

        for (; $i < $end; $i++) {
            if ($this->expected[$i] != $this->actual[$i]) {
                break;
            }
        }

        for (; $k >= $i && $j >= $i; $k--,$j--) {
            if ($this->expected[$j] != $this->actual[$k]) {
                break;
            }
        }

        if ($j < $i && $k < $i) {
            $expected = $this->expected;
            $actual   = $this->actual;
        } else {
            $expected = substr($this->expected, $i, ($j + 1 - $i));
            $actual   = substr($this->actual,   $i, ($k + 1 - $i));;

            if ($i <= $end && $i > 0) {
                $expected = '...' . $expected;
                $actual   = '...' . $actual;
            }
      
            if ($j < strlen($this->expected) - 1) {
                $expected .= '...';
            }

            if ($k < strlen($this->actual) - 1) {
                $actual .= '...';
            }

            return PHPUnit_Framework_Assert::format(
                $expected,
                $actual,
                parent::getMessage()
            );
        }
    }

    // }}}
}

/*
 * vim600:  et sw=2 ts=2 fdm=marker
 * vim<600: et sw=2 ts=2
 */
?>
