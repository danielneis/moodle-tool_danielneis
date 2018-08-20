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
 * Plugin edit
 *
 * @package    tool_danielneis
 * @copyright  2018 Daniel Neis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->dirroot.'/admin/tool/danielneis/lib.php');

$delete = required_param('delete', PARAM_INT);


if ($delete) {
    require_sesskey();
    if ($record = tool_danielneis_find($delete)) {
        $courseid = $record->courseid;
        require_login($courseid);

        $context = context_course::instance($courseid);
        require_capability('tool/danielneis:edit', $context);
        tool_danielneis_delete($delete);
        redirect(new moodle_url('/admin/tool/danielneis/index.php', array('id' => $courseid)));
    }
}
redirect(new moodle_url('/', array(), get_string('invalidrecord', 'tool_danielneis')));
