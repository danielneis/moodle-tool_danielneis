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
 * Contains the class used for the displaying the data table.
 *
 * @package    tool_danielneis
 * @copyright  2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

class tool_danielneis_table extends table_sql {

    public function __construct($uniqueid, $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $columns = ['id', 'name', 'description', 'completed', 'timecreated', 'timemodified'];
        $headers = [get_string('id', 'tool_danielneis'), get_string('name'), get_string('description'),
                    get_string('completed', 'tool_danielneis'), get_string('timecreated', 'tool_danielneis'),
                    get_string('timemodified', 'tool_danielneis')];

        $this->context = context_course::instance($courseid);
        if (has_capability('tool/danielneis:edit', $this->context)) {
            $columns[] = 'edit';
            $headers[] = '';
        }

        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);

        $this->define_baseurl($PAGE->url);

        $this->set_sql('id, name, description, descriptionformat, completed, timecreated, timemodified',
                '{tool_danielneis}', 'courseid = ?', [$courseid]);

    }

    protected function col_name($row) {
        return format_string($row->name);
    }

    protected function col_description($row) {
        $options = tool_danielneis_editor_options();
        $description = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php',
            $options['context']->id, 'tool_danielneis', 'record', $row->id, $options);
        return format_text($description, $row->descriptionformat, $options);
    }

    protected function col_edit($row) {
        $editurl = new moodle_url('/admin/tool/danielneis/edit.php', ['id' => $row->id]);
        $deleteurl = new moodle_url('/admin/tool/danielneis/delete.php', ['delete' => $row->id, 'sesskey' => sesskey()]);
        return html_writer::link($editurl, get_string('edit')) . '&nbsp;' .
               html_writer::link($deleteurl, get_string('delete'));
    }

    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }
}
