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
 * Authors block main class.
 *
 * @package     block_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/admin/tool/authors/classes/authors_manager.php');

/**
 * Block class for displaying authors.
 */
class block_authors extends block_base {
    /**
     * Initializes the block.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_authors');
    }

    /**
     * Returns the block content.
     *
     * @return stdClass The block content
     */
    public function get_content() {
        global $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Check capability.
        $context = context_course::instance($COURSE->id);
        if (!has_capability('tool/authors:viewblock', $context)) {
            return $this->content;
        }

        // Get configuration.
        $moduleid = isset($this->config->moduleid) ? (int)$this->config->moduleid : 0;

        if ($moduleid > 0) {
            // Show specific module authors.
            $minpercentage = get_config('tool_authors', 'minpercentage');
            if ($minpercentage === false) {
                $minpercentage = 1.0;
            }

            $html = \tool_authors\authors_manager::get_formatted_authors(
                $moduleid,
                true,  // Show photos.
                true,  // Show links.
                (float)$minpercentage
            );

            $this->content->text = $html;
        } else {
            // Show course-wide authors summary.
            $modules = \tool_authors\authors_manager::get_course_authors($COURSE->id);

            if (empty($modules)) {
                $this->content->text = html_writer::tag('p', get_string('noauthors', 'block_authors'), 
                    ['class' => 'text-muted']);
            } else {
                $html = '<div class="block-authors-summary">';
                $limit = isset($this->config->limit) ? (int)$this->config->limit : 5;
                $count = 0;

                foreach ($modules as $moduledata) {
                    if ($count >= $limit) {
                        break;
                    }

                    $cm = $moduledata['cm'];
                    $authors = $moduledata['authors'];

                    $html .= '<div class="module-entry mb-2">';
                    $html .= '<strong>' . format_string($cm->name) . '</strong><br>';

                    foreach ($authors as $author) {
                        $user = $author['user'];
                        $html .= '<small>' . fullname($user) . ' (' . $author['percentage'] . '%)</small><br>';
                    }

                    $html .= '</div>';
                    $count++;
                }

                $html .= '</div>';
                $this->content->text = $html;
            }
        }

        return $this->content;
    }

    /**
     * Defines where the block can be added.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'course-view' => true,
            'mod' => true,
        ];
    }

    /**
     * Allows the block to have configuration.
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Allows multiple instances of the block.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }
}
