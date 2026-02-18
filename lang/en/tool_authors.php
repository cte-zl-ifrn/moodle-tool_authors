<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Authors Tracker';
$string['authors'] = 'Authors';
$string['authors:viewpage'] = 'View authors page';
$string['authors:viewblock'] = 'View authors block';
$string['authors:viewfilter'] = 'View authors filter';
$string['authors:manage'] = 'Manage authors records';
$string['authors:admin'] = 'Administer authors plugin';

$string['authorspage'] = 'Authors';
$string['currentauthors'] = 'Current Authors';
$string['completehistory'] = 'Complete History';
$string['noauthors'] = 'No authors found for this course.';
$string['nohistory'] = 'No history found.';

$string['action'] = 'Action';
$string['actioncreate'] = 'Create';
$string['actionupdate'] = 'Update';
$string['actiondelete'] = 'Delete';
$string['timestamp'] = 'Timestamp';
$string['user'] = 'User';
$string['module'] = 'Module';
$string['moduletype'] = 'Module Type';
$string['modulename'] = 'Module Name';
$string['percentage'] = 'Percentage';
$string['actioncount'] = 'Actions';
$string['totalactions'] = 'Total actions: {$a}';

$string['settings'] = 'Authors Settings';
$string['minpercentage'] = 'Minimum percentage to display';
$string['minpercentage_desc'] = 'Minimum percentage of contribution required to display an author (default: 1%)';

$string['privacy:metadata:tool_authors_log'] = 'Log of authorship actions on course modules';
$string['privacy:metadata:tool_authors_log:courseid'] = 'The ID of the course';
$string['privacy:metadata:tool_authors_log:moduleid'] = 'The ID of the course module';
$string['privacy:metadata:tool_authors_log:userid'] = 'The ID of the user who performed the action';
$string['privacy:metadata:tool_authors_log:timestamp'] = 'The time when the action was performed';
$string['privacy:metadata:tool_authors_log:action'] = 'The type of action performed';
$string['privacy:metadata:tool_authors_log:modtype'] = 'The type of module';
$string['privacy:metadata:tool_authors_log:modname'] = 'The name of the module';
