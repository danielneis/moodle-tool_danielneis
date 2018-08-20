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
    $id = $DB->insert_record('tool_danielneis', $record);
    if (isset($data->description_editor)) {
        $context = context_course::instance($data->courseid);
        $data = file_postupdate_standard_editor($data, 'description',
            tool_danielneis_editor_options(), $context, 'tool_danielneis', 'record', $id);
        $updatedata = ['id' => $id, 'description' => $data->description, 'descriptionformat' => $data->descriptionformat];
        $DB->update_record('tool_danielneis', $updatedata);
    }
    return $id;
}

function tool_danielneis_update($data) {
    global $DB, $PAGE;
    if (isset($data->description_editor)) {
        $data = file_postupdate_standard_editor($data, 'description',
            tool_danielneis_editor_options(), $PAGE->context, 'tool_danielneis', 'record', $data->id);
    }
    $record = new stdclass();
    $record->id = $data->id;
    $record->name = $data->name;
    $record->completed = isset($data->completed) && $data->completed == 1;
    $record->timemodified = time();
    $record->description = $data->description;
    $record->descriptionformat = $data->descriptionformat;
    return $DB->update_record('tool_danielneis', $record);
}

function tool_danielneis_find($id) {
    global $DB;
    return $DB->get_record('tool_danielneis', ['id' => $id]);
}

function tool_danielneis_delete($id) {
    global $DB;
    return $DB->delete_records('tool_danielneis', ['id' => $id]);
}

function tool_danielneis_editor_options() {
    global $PAGE;
    return [
        'maxfiles' => -1,
        'maxbytes' => 0,
        'context' => $PAGE->context,
        'noclean' => true,
    ];
}

/**
 * Serve the embedded files.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param context $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function tool_danielneis_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {

    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    if ($filearea !== 'record') {
        return false;
    }

    require_login($course);
    require_capability('tool/danielneis:view', $context);

    $itemid = array_shift($args);

    $record = tool_danielneis_find($itemid);

    $filename = array_pop($args);

    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'tool_danielneis', $filearea, $itemid, $filepath, $filename);

    if (!$file) {
        return false;
    }

    send_stored_file($file, null, 0, $forcedownload, $options);
}
