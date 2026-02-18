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
 * Main page for authors tool.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use tool_authors\authors_manager;

$courseid = required_param('course', PARAM_INT);
$tab = optional_param('tab', 'authors', PARAM_ALPHA);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_login($course);
require_capability('tool/authors:viewpage', $context);

$PAGE->set_url('/admin/tool/authors/index.php', ['course' => $courseid, 'tab' => $tab]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('authorspage', 'tool_authors'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();

// Tabs.
$tabs = [];
$tabs[] = new tabobject('authors', 
    new moodle_url('/admin/tool/authors/index.php', ['course' => $courseid, 'tab' => 'authors']),
    get_string('currentauthors', 'tool_authors'));
$tabs[] = new tabobject('history', 
    new moodle_url('/admin/tool/authors/index.php', ['course' => $courseid, 'tab' => 'history']),
    get_string('completehistory', 'tool_authors'));

echo $OUTPUT->tabtree($tabs, $tab);

if ($tab === 'authors') {
    // Display current authors by module.
    $modules = authors_manager::get_course_authors($courseid);

    if (empty($modules)) {
        echo html_writer::tag('p', get_string('noauthors', 'tool_authors'), ['class' => 'alert alert-info']);
    } else {
        foreach ($modules as $moduledata) {
            $cm = $moduledata['cm'];
            $authors = $moduledata['authors'];

            echo html_writer::start_tag('div', ['class' => 'module-authors card mb-3']);
            echo html_writer::start_tag('div', ['class' => 'card-body']);

            $totalactions = 0;
            foreach ($authors as $author) {
                $totalactions += $author['actioncount'];
            }

            echo html_writer::tag('h5', format_string($cm->name) . ' (' . $cm->modname . ')', ['class' => 'card-title']);
            echo html_writer::tag('p', get_string('totalactions', 'tool_authors', $totalactions), ['class' => 'text-muted']);

            echo html_writer::start_tag('ul', ['class' => 'list-unstyled']);
            foreach ($authors as $author) {
                $user = $author['user'];
                echo html_writer::start_tag('li', ['class' => 'author-item mb-2']);
                echo $OUTPUT->user_picture($user, ['size' => 35]);
                echo ' ' . fullname($user);
                echo ' (' . $author['percentage'] . '%)';
                echo ' - ' . get_string('actioncount', 'tool_authors') . ': ' . $author['actioncount'];
                echo html_writer::end_tag('li');
            }
            echo html_writer::end_tag('ul');

            echo html_writer::end_tag('div');
            echo html_writer::end_tag('div');
        }
    }
} else if ($tab === 'history') {
    // Display complete history.
    $history = authors_manager::get_course_history($courseid);

    if (empty($history)) {
        echo html_writer::tag('p', get_string('nohistory', 'tool_authors'), ['class' => 'alert alert-info']);
    } else {
        $table = new html_table();
        $table->head = [
            get_string('timestamp', 'tool_authors'),
            get_string('user', 'tool_authors'),
            get_string('action', 'tool_authors'),
            get_string('moduletype', 'tool_authors'),
            get_string('modulename', 'tool_authors'),
        ];
        $table->attributes['class'] = 'generaltable table table-striped';

        foreach ($history as $entry) {
            $row = [];
            $row[] = userdate($entry->timestamp, get_string('strftimedatetime', 'langconfig'));
            $row[] = fullname($entry);
            $row[] = get_string('action' . $entry->action, 'tool_authors');
            $row[] = $entry->modtype;
            $row[] = format_string($entry->modname);
            $table->data[] = $row;
        }

        echo html_writer::table($table);
    }
}

echo $OUTPUT->footer();
