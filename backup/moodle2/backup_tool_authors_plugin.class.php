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
 * Backup task for tool_authors.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

/**
 * Backup task class for tool_authors.
 */
class backup_tool_authors_plugin extends backup_tool_plugin {
    /**
     * Define the structure of the backup.
     *
     * @return backup_plugin_element
     */
    protected function define_course_plugin_structure() {
        $plugin = $this->get_plugin_element();

        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        $authorslog = new backup_nested_element('authors_log');

        $author = new backup_nested_element('author', ['id'], [
            'courseid', 'moduleid', 'userid', 'timestamp', 'action', 'modtype', 'modname'
        ]);

        $plugin->add_child($pluginwrapper);
        $pluginwrapper->add_child($authorslog);
        $authorslog->add_child($author);

        $author->set_source_table('tool_authors_log', ['courseid' => backup::VAR_COURSEID]);

        return $plugin;
    }
}
