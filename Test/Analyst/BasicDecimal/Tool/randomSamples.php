<?php
// vim: fenc=utf-8:ft=php:ai:si:ts=4:sw=4:et:

/**
 * Generate random samples.
 *
 * @filesource
 * @copyright   Copyright (c) 2008-2011 David Smith
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Analyst_BasicDecimal
 * @version     $Id$
*/

/**
 * Return an assoc. of timestamp/scalar pairs.
 *
 * @param string    $type           See Hashmark_Core::getValidScalarTypes() for options.
 * @param mixed     $minTime        UNIX timestamp or strtotime() string.
 * @param mixed     $maxTime        UNIX timestamp or strtotime() string.
 * @param int       $count          Desired amount. Not guaranteed.
 * @param boolean   $uniqueTime     Keep timestamps unique.
 * @param boolean   $uniqueValue    Keep values unique.
 * @param int       $minValue       Inclusive-minimum value. For 'decimal' type only.
 * @param int       $maxValue       Inclusive-maximum value. For 'decimal' type only.
 * @param boolean   $sortByTime     Sorts final set by time ascending.
 *
 * @return Array    
 *
 *      If $uniqueValue is true:
 *          Keys = UNIX timestamps; values = sample values.
 *      Else:
 *          Keys = '<time_id>=<UNIX timestamp>' strings; values = sample values.
 *
 *          Time IDs prevent loss of duplicate times when we eventually array_combine()
 *          $randomTimes (as keys) with $randomValues.
 *
 *          Client code can then list($time) = explode('=', $sample).
 */
function hashmark_random_samples($type,
                                 $minTime,
                                 $maxTime,
                                 $count,
                                 $uniqueTime = false,
                                 $uniqueValue = false,
                                 $minValue = null,
                                 $maxValue = null,
                                 $sortByTime = false)
{
    // Hashmark_TestCase
    require_once dirname(__FILE__) . '/../../../bootstrap.php';

    if (is_string($minTime)) {
        $minTime = strtotime($minTime);
    }
    if (is_string($maxTime)) {
        $maxTime = strtotime($maxTime);
    }

    $now = time();
    $minTimeDelta = $minTime - $now;
    $maxTimeDelta = $maxTime - $now;

    // Avoid infinite/slow while().
    $maxPasses = 4 * $count;

    $randomTimes = array();
    $randomValues = array();

    while ($count && $maxPasses) {
        $t = $now + mt_rand($minTimeDelta, $maxTimeDelta);
        
        if ($uniqueTime && isset($randomTimes[$t])) {
            continue;
        }

        if ($uniqueTime) {
            $randomTimes[$t] = 1; 
        } else {
            $randomTimes[] = $t . '=' .  uniqid(mt_rand(), true);
        }

        if ('string' == $type) {
            $v = Hashmark_TestCase::randomString();
        } else {
            $v = Hashmark_TestCase::randomDecimal();
        }

        if (!is_null($minValue) && $v < $minValue) {
            continue;
        }
        if (!is_null($maxValue) && $v > $maxValue) {
            continue;
        }

        if ($uniqueValue && isset($randomValues[$v])) {
            continue;
        }
        
        if ($uniqueValue) {
            $randomValues[$v] = 1;
        } else {
            $randomValues[] = $v;
        }

        $count--;
        $maxPasses--;
    }

    if ($uniqueTime) {
        $randomTimes = array_keys($randomTimes);
    }
    if ($uniqueValue) {
        $randomValues = array_keys($randomValues);
    }

    if ($sortByTime) {
        sort($randomTimes);
    }

    return array_combine($randomTimes, $randomValues);
}
