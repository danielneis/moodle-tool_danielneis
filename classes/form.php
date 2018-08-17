<?php

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class tool_danielneis_form extends moodleform {

    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('text', 'name', get_string('name')); // Add elements to your form
        $mform->setType('name', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('name', '');        //Default value

        $mform->addElement('checkbox', 'completed', get_string('completed', 'tool_danielneis'));

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        if ($DB->record_exists_select('tool_danielneis', 'name = :name AND id <> :id AND courseid = :courseid',
                ['name' => $data['name'], 'id' => $data['id'], 'courseid' => $data['courseid']])) {

            $errors['name'] = get_string('errornameexists', 'tool_danielneis');
        }
        return $errors;
    }
}
