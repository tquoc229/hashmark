<?php
// vim: fenc=utf-8:ft=php:ai:si:ts=4:sw=4:et:

/**
 * Hashmark_TestCase_Agent_ScalarValue
 *
 * @filesource
 * @copyright   Copyright (c) 2008-2011 David Smith
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Agent
 * @version     $Id$
*/

/**
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Agent
 */
class Hashmark_TestCase_Agent_ScalarValue extends Hashmark_TestCase_Agent
{
    /**
     * @test
     * @group Agent
     * @group Test
     * @group runsAgent
     * @group run
     */
    public function runsAgent()
    {
        $expectedFields = array();
        $expectedFields['name'] = self::randomString();
        $expectedFields['value'] = 'test_value';
        $expectedFields['type'] = 'string';

        $expectedId = Hashmark::getModule('Core', '', $this->_db)->createScalar($expectedFields);
        $sample = Hashmark::getModule('Agent', 'ScalarValue')->run(array('scalarId' => $expectedId));

        $this->assertEquals($expectedFields['value'], $sample);
    }
}
