<?php
// vim: fenc=utf-8:ft=php:ai:si:ts=4:sw=4:et:

/**
 * Hashmark_AllTests_Core
 *
 * @filesource
 * @copyright   Copyright (c) 2008-2011 David Smith
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Core
 * @version     $Id$
*/

/**
 * Turns on logging/error-reporting, loads PHPUnit, etc.
 */
require_once dirname(__FILE__) . '/../bootstrap.php';

/**
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Core
 */
class Hashmark_AllTests_Core
{
    /**
     * Auto-discover all tests.
     *
     * @return PHPUnit_Framework_TestSuite  Suite covering all implementations.
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite(__METHOD__);
        
        // Hashmark_Core
        require_once HASHMARK_ROOT_DIR . '/Core.php';

        // Hashmark_TestCase_Core
        require_once HASHMARK_ROOT_DIR . '/Test/Core.php';
        
        $suite->addTestSuite('Hashmark_TestCase_Core');

        return $suite;
    }
}
