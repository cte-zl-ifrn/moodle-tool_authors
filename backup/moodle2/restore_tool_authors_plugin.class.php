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
 * Restore task for tool_authors.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * Restore task class for tool_authors.
 */
class restore_tool_authors_plugin extends restore_tool_plugin {
    /**
     * Define the structure to restore.
     *
     * @return array
     */
    protected function define_course_plugin_structure() {
        $paths = [];

        $elename = 'author';
        $elepath = $this->get_pathfor('/authors_log/author');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;
    }

    /**
     * Process author log entry restoration.
     *
     * @param array $data
     */
    public function process_author($data) {
        global $DB;

        $data = (object)$data;

        // Map old courseid to new courseid.
        $data->courseid = $this->get_mappingid('course', $data->courseid);

        // Map old moduleid to new moduleid.
        $data->moduleid = $this->get_mappingid('course_module', $data->moduleid);

        // Map old userid to new userid.
        $data->userid = $this->get_mappingid('user', $data->userid);

        // Only insert if we have valid mappings.
        if ($data->courseid && $data->moduleid && $data->userid) {
            // Check if entry already exists (avoid duplicates on multiple restores).
            $existing = $DB->get_record('tool_authors_log', [
                'courseid' => $data->courseid,
                'moduleid' => $data->moduleid,
                'userid' => $data->userid,
                'timestamp' => $data->timestamp,
                'action' => $data->action,
            ]);

            if (!$existing) {
                $DB->insert_record('tool_authors_log', $data);
            }
        }
    }
}
