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
 * Block configuration form.
 *
 * @package     block_authors
 * @copyright   2026 CTE-ZL-IFRN
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Block configuration form class.
 */
class block_authors_edit_form extends block_edit_form {
    /**
     * Add specific configuration fields.
     *
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform) {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_moduleid', get_string('moduleid', 'block_authors'));
        $mform->setType('config_moduleid', PARAM_INT);
        $mform->addHelpButton('config_moduleid', 'moduleid', 'block_authors');

        $mform->addElement('text', 'config_limit', get_string('limit', 'block_authors'));
        $mform->setType('config_limit', PARAM_INT);
        $mform->setDefault('config_limit', 5);
        $mform->addHelpButton('config_limit', 'limit', 'block_authors');
    }
}
