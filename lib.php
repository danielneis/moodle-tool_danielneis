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
 * Plugin lib
 *
 * @package    tool_danielneis
 * @copyright  2018 Daniel Neis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function tool_danielneis_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {
    if (has_capability('tool/danielneis:view', $context)) {
        $id = $context->instanceid;
        $urltext = get_string('pluginlink', 'tool_danielneis');
        $url = new moodle_url('/admin/tool/danielneis/index.php', array('id' => $id));
        $parentnode->add($urltext, $url);
    }
}

function tool_danielneis_insert($data) {
    global $DB;
    $record = new stdclass();
    $record->name = $data->name;
    $record->completed = isset($data->completed) && $data->completed == 1;
    $record->courseid = $data->courseid;
    $record->timecreated = time();
    return $DB->insert_record('tool_danielneis', $record);
}

function tool_danielneis_update($data) {
    global $DB;
    $record = new stdclass();
    $record->id = $data->id;
    $record->name = $data->name;
    $record->completed = isset($data->completed) && $data->completed == 1;
    $record->timemodified = time();
    return $DB->update_record('tool_danielneis', $record);
}

function tool_danielneis_find($id) {
    global $DB;
    return $DB->get_record('tool_danielneis', ['id' => $id]);
}
