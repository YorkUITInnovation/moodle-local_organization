<?php

namespace local_organization;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class campus_form extends \moodleform
{

    protected function definition()
    {

        $formdata = $this->_customdata['formdata'];

        // Create form object
        $mform = &$this->_form;

        $context = \context_system::instance();

        $mform->addElement(
            'hidden',
            'id'
        );
        $mform->setType(
            'id',
            PARAM_INT
        );

        // Name form element
        $mform->addElement(
            'text',
            'name',
            get_string('name', 'local_organization')
        );
        $mform->setType(
            'name', PARAM_TEXT
        );
        $mform->addRule(
            'name',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // shortname form element
        $mform->addElement(
            'text',
            'shortname',
            get_string('short_name', 'local_organization')
        );
        $mform->setType(
            'shortname', PARAM_TEXT
        );
        $mform->addRule(
            'shortname',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // id_number form element
        $mform->addElement(
            'text',
            'id_number',
            get_string('id_number', 'local_organization')
        );
        $mform->setType(
            'id_number', PARAM_TEXT
        );


        $this->add_action_buttons();
        $this->set_data($formdata);
    }

    // Perform some extra moodle validation
    public function validation($data, $files)
    {
        global $DB;

        $errors = parent::validation($data, $files);


        return $errors;
    }

}
