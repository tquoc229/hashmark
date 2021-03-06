#!/usr/bin/php
<?php
// vim: fenc=utf-8:ft=php:ai:si:ts=4:sw=4:et:

/**
 * Run scheduled agents.
 *
 * @filesource
 * @copyright   Copyright (c) 2008-2011 David Smith
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @package     Hashmark
 * @subpackage  Cron
 * @version     $Id$
*/

/**
 * For getModule().
 */
require_once dirname(__FILE__) . '/../Hashmark.php';

$db = Hashmark::getModule('DbHelper')->openDb('cron');
$core = Hashmark::getModule('Core', '', $db);
$partition = Hashmark::getModule('Partition', '', $db);

$scheduledAgents = $core->getScheduledAgents();
if (empty($scheduledAgents)) {
    exit;
}

// Reuse previously loaded agent objects since they have no properties.
$cache = array();

foreach ($scheduledAgents as $scalarAgent) {
    if ('Running' == $scalarAgent['status']) {
        $core->setScalarAgentStatus($scalarAgent['id'], 'Unscheduled',
                                    'Last run did not finish.');
        continue;
    }

    if (!isset($cache[$scalarAgent['name']])) {
        try {
            $cache[$scalarAgent['name']] = Hashmark::getModule('Agent',
                                                               $scalarAgent['name']);
        } catch (Exception $e) {
            $error = sprintf('Agent "%s" module missing: %s',
                             $scalarAgent['name'], $e->getMessage());
            $core->setScalarAgentStatus($scalarAgent['id'], 'Unscheduled', $error);
            continue;
        }
    }

    if (!$cache[$scalarAgent['name']]) {
        $error = "Agent '{$scalarAgent['name']}' was missing";
        $core->setScalarAgentStatus($scalarAgent['id'], 'Unscheduled', $error);
        continue;
    }

    $core->setScalarAgentStatus($scalarAgent['id'], 'Running');

    $value = $cache[$scalarAgent['name']]->run($scalarAgent);

    // run() received $scalarAgent by-ref and can apply indepedent logic
    // to set a new status, error message, etc.
    $core->setScalarAgentStatus($scalarAgent['id'], $scalarAgent['status'],
                                $scalarAgent['error'], time());
}
