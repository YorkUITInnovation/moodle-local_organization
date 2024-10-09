<?php

namespace local_organization;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class advisors_form extends \moodleform
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


        // Get all users
        // ws function name - get_users
        $user_options = ['multiple' => true, 'ajax' => 'local_organization/users',   'noselectionstring' => get_string('user')];
        $users = [];
        // Add autocomplete element for user
        /*$mform->addElement(
            'autocomplete',
            'id',
            get_string('users', 'local_organization'),
            $advisors,
            $options
        );*/
        // Add autocomplete element for user using AMD
        $mform->addElement(
            'autocomplete',
            'userid',
            get_string('users', 'local_organization'),
            $users,
            $user_options
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

        // role form element
        $mform->addElement(
            'text',
            'role',
            get_string('role', 'local_organization')
        );
        $mform->setType(
            'role', PARAM_TEXT
        );
        $mform->addRule(
            'role',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // instance id or actual unit or department form element
        $mform->addElement(
            'text',
            'Name of Unit/Department',
            get_string('context', 'local_organization')
        );
        $mform->setType(
            'context', PARAM_TEXT
        );


        $this->add_action_buttons();
        $this->set_data($formdata);
    }

}