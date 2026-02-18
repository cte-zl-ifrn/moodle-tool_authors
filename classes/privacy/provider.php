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
 * Privacy API implementation for tool_authors.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_authors\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider for tool_authors.
 */
class provider implements 
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Returns meta data about this system.
     *
     * @param collection $collection The collection to add metadata to
     * @return collection The updated collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'tool_authors_log',
            [
                'courseid' => 'privacy:metadata:tool_authors_log:courseid',
                'moduleid' => 'privacy:metadata:tool_authors_log:moduleid',
                'userid' => 'privacy:metadata:tool_authors_log:userid',
                'timestamp' => 'privacy:metadata:tool_authors_log:timestamp',
                'action' => 'privacy:metadata:tool_authors_log:action',
                'modtype' => 'privacy:metadata:tool_authors_log:modtype',
                'modname' => 'privacy:metadata:tool_authors_log:modname',
            ],
            'privacy:metadata:tool_authors_log'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search
     * @return contextlist The contextlist containing the list of contexts
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT DISTINCT ctx.id
                FROM {tool_authors_log} tal
                JOIN {course} c ON c.id = tal.courseid
                JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel
                WHERE tal.userid = :userid";

        $params = [
            'contextlevel' => CONTEXT_COURSE,
            'userid' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();
        $userid = $user->id;

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel == CONTEXT_COURSE) {
                $courseid = $context->instanceid;

                $sql = "SELECT *
                        FROM {tool_authors_log}
                        WHERE userid = :userid AND courseid = :courseid
                        ORDER BY timestamp DESC";

                $records = $DB->get_records_sql($sql, ['userid' => $userid, 'courseid' => $courseid]);

                if (!empty($records)) {
                    $data = [];
                    foreach ($records as $record) {
                        $data[] = (object)[
                            'timestamp' => \core_privacy\local\request\transform::datetime($record->timestamp),
                            'action' => $record->action,
                            'modtype' => $record->modtype,
                            'modname' => $record->modname,
                        ];
                    }

                    writer::with_context($context)->export_data(
                        [get_string('pluginname', 'tool_authors')],
                        (object)$data
                    );
                }
            }
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The context to delete data in
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        // Authors log is permanent and cannot be deleted according to Brazilian Law requirements.
        // This plugin maintains inalienable intellectual authorship records.
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        // Authors log is permanent and cannot be deleted according to Brazilian Law requirements.
        // This plugin maintains inalienable intellectual authorship records.
    }
}
