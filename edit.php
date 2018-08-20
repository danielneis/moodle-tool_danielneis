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

$id = optional_param('id', 0, PARAM_INT);
if ($id) {
    $record = tool_danielneis_find($id);
    $courseid = $record->courseid;
} else {
    $courseid = required_param('courseid', PARAM_INT);
    $record = (object)array('courseid' => $courseid);
}

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('tool/danielneis:edit', $context);

$mform = new tool_danielneis_form();
if (!empty($record->id)) {
    file_prepare_standard_editor($record, 'description',
        tool_danielneis_editor_options(),
        $PAGE->context, 'tool_danielneis', 'record', $record->id);
}
$mform->set_data($record);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/tool/danielneis/index.php', array('id' => $courseid)));
} else if ($data = $mform->get_data()) {
    if ($data->id) {
        tool_danielneis_update($data);
    } else {
        tool_danielneis_insert($data);
    }
    redirect(new moodle_url('/admin/tool/danielneis/index.php', array('id' => $courseid)));
} else {

    $url = new moodle_url('/admin/tool/danielneis/edit.php', array('courseid' => $courseid));
    $PAGE->set_url($url);
    $pagetitle = get_string('addtitle', 'tool_danielneis');
    $PAGE->set_title($pagetitle);
    $PAGE->set_heading(get_string('addtitle', 'tool_danielneis'));

    echo $OUTPUT->header(),
         $mform->display(),
         $OUTPUT->footer();
}
