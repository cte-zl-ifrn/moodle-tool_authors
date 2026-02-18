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
 * Plugin lib functions.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extend the course navigation.
 *
 * @param navigation_node $navigation The navigation node
 * @param stdClass $course The course object
 * @param context_course $context The course context
 */
function tool_authors_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('tool/authors:viewpage', $context)) {
        $url = new moodle_url('/admin/tool/authors/index.php', ['course' => $course->id]);
        $node = navigation_node::create(
            get_string('authorspage', 'tool_authors'),
            $url,
            navigation_node::TYPE_SETTING,
            null,
            'tool_authors',
            new pix_icon('i/report', '')
        );
        $navigation->add_node($node);
    }
}
