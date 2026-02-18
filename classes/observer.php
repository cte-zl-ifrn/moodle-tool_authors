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
 * Event observer for tool_authors.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_authors;

/**
 * Event observer for course module events.
 */
class observer {
    /**
     * Handle course module created event.
     *
     * @param \core\event\course_module_created $event
     */
    public static function course_module_created(\core\event\course_module_created $event) {
        self::log_action($event, 'create');
    }

    /**
     * Handle course module updated event.
     *
     * @param \core\event\course_module_updated $event
     */
    public static function course_module_updated(\core\event\course_module_updated $event) {
        self::log_action($event, 'update');
    }

    /**
     * Handle course module deleted event.
     *
     * @param \core\event\course_module_deleted $event
     */
    public static function course_module_deleted(\core\event\course_module_deleted $event) {
        self::log_action($event, 'delete');
    }

    /**
     * Log the action to the database.
     *
     * @param \core\event\base $event
     * @param string $action
     */
    private static function log_action(\core\event\base $event, string $action) {
        global $DB;

        $other = $event->other;
        $modname = isset($other['modulename']) ? $other['modulename'] : '';
        $instancename = isset($other['name']) ? $other['name'] : '';

        $data = new \stdClass();
        $data->courseid = $event->courseid;
        $data->moduleid = $event->contextinstanceid;
        $data->userid = $event->userid;
        $data->timestamp = $event->timecreated;
        $data->action = $action;
        $data->modtype = $modname;
        $data->modname = $instancename;

        $DB->insert_record('tool_authors_log', $data);
    }
}
