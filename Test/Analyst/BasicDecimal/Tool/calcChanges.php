<?php
// vim: fenc=utf-8:ft=php:ai:si:ts=4:sw=4:et:

/**
 * Use bcmath to calculate expected values for Test/Analyst/BasicDecimal/Data/provider.php.
 *
 * @filesource
 * @copyright   Copyright (c) 2008-2011 David Smith
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @package     Hashmark-Test
 * @subpackage  Hashmark_Analyst_BasicDecimal
 * @version     $Id$
*/

$sampleProviders = Hashmark_TestCase_Analyst_BasicDecimal::provideFullSamplesData();

$expValues = array();

foreach ($sampleProviders as $sampleProviderName => $sampleProviderData) {
    // Collect and sort in-range values.
    $inRangeSamples = array();
    foreach ($sampleProviderData['samples'] as $sample) {
        list($end, $value, , $isInRange) = $sample;

        if ($isInRange) {
            $inRangeSamples[$end] = $value;
        }
    }
    ksort($inRangeSamples);

    // Collect value changes.
    $expValues[$sampleProviderName] = array();
    $lastValue = null;
    foreach ($inRangeSamples as $end => $value) {
        if (!is_null($lastValue)) {
            $expValues[$sampleProviderName][$end] = bcsub($value, $lastValue);
        }
        $lastValue = $value;
    }
}
