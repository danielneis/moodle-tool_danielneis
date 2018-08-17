<?php

require('../../../config.php');
require_once($CFG->dirroot.'/admin/tool/danielneis/lib.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('tool/danielneis:edit', $context);

$mform = new tool_danielneis_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/tool/danielneis/index.php', array('id' => $courseid)));
} else if ($data = $mform->get_data()) {
    tool_danielneis_insert($data);
    redirect(new moodle_url('/admin/tool/danielneis/index.php', array('id' => $courseid)));
} else {
    $mform->set_data((object)array('courseid' => $courseid));

    $url = new moodle_url('/admin/tool/danielneis/edit.php', array('courseid' => $courseid));
    $PAGE->set_url($url);
    $pagetitle = get_string('addtitle', 'tool_danielneis');
    $PAGE->set_title($pagetitle);
    $PAGE->set_heading(get_string('addtitle', 'tool_danielneis'));

    echo $OUTPUT->header(),
	 $mform->display(),
         $OUTPUT->footer();
}
