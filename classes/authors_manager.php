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
 * Authors log manager class.
 *
 * @package     tool_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_authors;

/**
 * Class for managing authors log data.
 */
class authors_manager {
    /**
     * Get authors for a specific course module.
     *
     * @param int $moduleid Course module ID
     * @return array Array of authors with their statistics
     */
    public static function get_module_authors(int $moduleid): array {
        global $DB;

        $sql = "SELECT userid, COUNT(*) as actioncount
                FROM {tool_authors_log}
                WHERE moduleid = :moduleid
                GROUP BY userid
                ORDER BY actioncount DESC";

        $records = $DB->get_records_sql($sql, ['moduleid' => $moduleid]);

        $totalactions = 0;
        foreach ($records as $record) {
            $totalactions += $record->actioncount;
        }

        $authors = [];
        foreach ($records as $record) {
            $user = $DB->get_record('user', ['id' => $record->userid]);
            if ($user) {
                $percentage = $totalactions > 0 ? round(($record->actioncount / $totalactions) * 100, 2) : 0;
                $authors[] = [
                    'user' => $user,
                    'actioncount' => $record->actioncount,
                    'percentage' => $percentage,
                ];
            }
        }

        return $authors;
    }

    /**
     * Get authors for all modules in a course.
     *
     * @param int $courseid Course ID
     * @return array Array of modules with their authors
     */
    public static function get_course_authors(int $courseid): array {
        global $DB;

        $sql = "SELECT DISTINCT moduleid
                FROM {tool_authors_log}
                WHERE courseid = :courseid
                ORDER BY moduleid";

        $moduleids = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        $modules = [];
        foreach ($moduleids as $record) {
            $cm = get_coursemodule_from_id('', $record->moduleid);
            if ($cm) {
                $modules[] = [
                    'cm' => $cm,
                    'authors' => self::get_module_authors($record->moduleid),
                ];
            }
        }

        return $modules;
    }

    /**
     * Get complete history for a course module.
     *
     * @param int $moduleid Course module ID
     * @return array Array of log entries
     */
    public static function get_module_history(int $moduleid): array {
        global $DB;

        $sql = "SELECT l.*, u.firstname, u.lastname, u.email
                FROM {tool_authors_log} l
                JOIN {user} u ON l.userid = u.id
                WHERE l.moduleid = :moduleid
                ORDER BY l.timestamp DESC";

        return $DB->get_records_sql($sql, ['moduleid' => $moduleid]);
    }

    /**
     * Get complete history for a course.
     *
     * @param int $courseid Course ID
     * @return array Array of log entries
     */
    public static function get_course_history(int $courseid): array {
        global $DB;

        $sql = "SELECT l.*, u.firstname, u.lastname, u.email
                FROM {tool_authors_log} l
                JOIN {user} u ON l.userid = u.id
                WHERE l.courseid = :courseid
                ORDER BY l.timestamp DESC";

        return $DB->get_records_sql($sql, ['courseid' => $courseid]);
    }

    /**
     * Get formatted author display for a module.
     *
     * @param int $moduleid Course module ID
     * @param bool $showphotos Include user photos
     * @param bool $showlinks Include profile links
     * @param float $minpercentage Minimum percentage to show author
     * @return string HTML formatted author display
     */
    public static function get_formatted_authors(int $moduleid, bool $showphotos = true, 
                                                  bool $showlinks = true, float $minpercentage = 1.0): string {
        global $OUTPUT;

        $authors = self::get_module_authors($moduleid);
        $html = '';

        foreach ($authors as $author) {
            if ($author['percentage'] < $minpercentage) {
                continue;
            }

            $user = $author['user'];
            $html .= '<div class="author-entry">';

            if ($showphotos) {
                $html .= $OUTPUT->user_picture($user, ['size' => 35, 'class' => 'author-photo']);
            }

            $html .= '<span class="author-name">';
            $html .= fullname($user);
            $html .= ' (' . $author['percentage'] . '%)';
            $html .= '</span>';

            if ($showlinks) {
                // Check for custom profile fields for Lattes/LinkedIn links.
                $profilefields = self::get_user_profile_links($user->id);
                if (!empty($profilefields)) {
                    $html .= ' <span class="author-links">';
                    foreach ($profilefields as $field) {
                        $html .= '<a href="' . s($field['url']) . '" target="_blank">[' . s($field['name']) . ']</a> ';
                    }
                    $html .= '</span>';
                }
            }

            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Get user profile links (Lattes, LinkedIn, etc).
     *
     * @param int $userid User ID
     * @return array Array of profile links
     */
    private static function get_user_profile_links(int $userid): array {
        global $DB;

        $links = [];

        // Get custom profile fields that contain URLs.
        $sql = "SELECT f.shortname, f.name, d.data
                FROM {user_info_field} f
                JOIN {user_info_data} d ON d.fieldid = f.id
                WHERE d.userid = :userid 
                AND f.datatype = 'text'
                AND (LOWER(f.shortname) LIKE '%lattes%' 
                     OR LOWER(f.shortname) LIKE '%linkedin%'
                     OR LOWER(f.shortname) LIKE '%link%')
                AND d.data LIKE 'http%'";

        $records = $DB->get_records_sql($sql, ['userid' => $userid]);

        foreach ($records as $record) {
            $links[] = [
                'name' => $record->name,
                'url' => $record->data,
            ];
        }

        return $links;
    }
}
