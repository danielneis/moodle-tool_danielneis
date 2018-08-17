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
 * Plugin index page
 *
 * @package    tool_danielneis
 * @copyright  2018 Daniel Neis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$id = required_param('id', PARAM_INT);

require_login($id);

$context = context_course::instance($id);
require_capability('tool/danielneis:view', $context);

$url = new moodle_url('/admin/tool/danielneis/index.php', array('id' => $id));
$PAGE->set_url($url);
$pagetitle = get_string('indextitle', 'tool_danielneis');
$PAGE->set_title($pagetitle);
$PAGE->set_heading(get_string('pluginname', 'tool_danielneis'));

echo $OUTPUT->header(),
     $OUTPUT->heading($pagetitle),
     html_writer::tag('h2', get_string('hello', 'tool_danielneis', $id));

$configcount = $DB->count_records('config');
echo html_writer::tag('p', get_string('coursedescription', 'tool_danielneis', get_course($id))),
     html_writer::tag('p', get_string('configcount', 'tool_danielneis', $configcount));

if (has_capability('tool/danielneis:edit', $context)) {
     echo html_writer::link(new moodle_url('/admin/tool/danielneis/edit.php', array('courseid' => $id)), get_string('add'));
}

$table = new tool_danielneis_table('uniqid', $id);

echo $table->out(10, true),
     $OUTPUT->footer();
