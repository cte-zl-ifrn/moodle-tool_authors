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
 * Authors filter main class.
 *
 * @package     filter_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/admin/tool/authors/classes/authors_manager.php');

/**
 * Filter class for displaying authors.
 */
class filter_authors extends moodle_text_filter {
    /**
     * Apply the filter to the text.
     *
     * @param string $text The text to filter
     * @param array $options Filter options
     * @return string The filtered text
     */
    public function filter($text, array $options = []) {
        global $COURSE;

        // Check capability.
        $context = context_course::instance($COURSE->id);
        if (!has_capability('tool/authors:viewfilter', $context)) {
            return $text;
        }

        // Pattern: {authors:cmid123}.
        $pattern = '/\{authors:cmid(\d+)\}/';

        return preg_replace_callback($pattern, function($matches) {
            $moduleid = (int)$matches[1];
            
            try {
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

                return '<div class="authors-filter-display">' . $html . '</div>';
            } catch (Exception $e) {
                return '<div class="alert alert-warning">Error loading authors</div>';
            }
        }, $text);
    }
}
