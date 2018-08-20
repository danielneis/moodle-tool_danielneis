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
 * Plugin tests
 *
 * @package    tool_danielneis
 * @copyright  2018 Daniel Neis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class tool_danielneis_sample_testcase extends advanced_testcase {
    public function test_adding() {
        global $DB, $CFG;
        require_once($CFG->dirroot.'/admin/tool/danielneis/lib.php');

        $this->resetAfterTest(true);

        $data = new stdclass();
        $data->name = 'Fake name';
        $data->courseid = 2;

        tool_danielneis_insert($data);

        $this->assertEquals(1, count($DB->get_records('tool_danielneis', [])));
    }
}
