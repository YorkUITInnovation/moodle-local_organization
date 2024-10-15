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

        // Add hidden element for instance_id
        $mform->addElement(
            'hidden',
            'instance_id'
        );
        $mform->setType(
            'instance_id',
            PARAM_INT
        );

        // Add hidden element for user_context
        $mform->addElement(
            'hidden',
            'user_context'
        );
        $mform->setType(
            'user_context',
            PARAM_TEXT
        );
        // Add hidden element for user_context
        $mform->addElement(
            'hidden',
            'campus_id'
        );
        $mform->setType(
            'campus_id',
            PARAM_INT
        );
        // Add hidden element for user_context
        $mform->addElement(
            'hidden',
            'unit_id'
        );
        $mform->setType(
            'unit_id',
            PARAM_INT
        );

        // Get all users
        // Set value for multiple users based on whether the record has id != 0
        if ($formdata->id != 0) {
            $multiple_users = false;
        } else {
            // Add more than one user at a time to the role
            $multiple_users = true;
        }
        // ws function name - get_users
        $user_options = ['multiple' => $multiple_users, 'ajax' => 'local_organization/users',   'noselectionstring' => get_string('user')];
        $users = [];
        // Add autocomplete element for user using AMD
        $mform->addElement(
            'autocomplete',
            'user_id',
            get_string('users', 'local_organization'),
            $users,
            $user_options
        );
        // User_id filed is required
        $mform->addRule(
            'user_id',
            null,
            'required',
            null,
            'client'
        );

        // Get role data
        $role_options = ['multiple' => false, 'ajax' => 'local_organization/roles',   'noselectionstring' => get_string('role')];
        $roles = [];
        // Add autocomplete element for user using AMD
        $mform->addElement(
            'autocomplete',
            'role_id',
            get_string('role'),
            $roles,
            $role_options
        );
        // Role_id filed is required
        $mform->addRule(
            'role_id',
            null,
            'required',
            null,
            'client'
        );

        $this->add_action_buttons();
        $this->set_data($formdata);
    }

}
