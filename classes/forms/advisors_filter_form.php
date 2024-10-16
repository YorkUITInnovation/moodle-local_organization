<?php

namespace local_organization;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class advisors_filter_form extends \moodleform
{
    public function definition()
    {
        GLOBAL $USER;
        $formdata = $this->_customdata['formdata'];
        $mform = $this->_form;

        // Conditionally show button groups based on capability .. there might be a better way to do this with just an element but I'm not sure yet
        $system_context = \context_system::instance();

        // Define no submit button
//        $mform->registerNoSubmitButton('resetbutton');

        // created to return campus_id when submitting/reset/filter
        $mform->addElement(
            'hidden',
            'campus_id'
        );
        $mform->setType(
            'campus_id',
            PARAM_INT
        );
        // created to return campus_id when submitting/reset/filter
        $mform->addElement(
            'hidden',
            'instance_id'
        );
        $mform->setType(
            'instance_id',
            PARAM_INT
        );
        $mform->addElement(
            'hidden',
            'unit_id'
        );
        $mform->setType(
            'unit_id',
            PARAM_INT
        );
        $mform->addElement(
            'hidden',
            'user_context'
        );
        $mform->setType(
            'user_context',
            PARAM_TEXT
        );

        // Based on user context, add a button to return to the proper context
        if ($formdata->user_context == base::CONTEXT_UNIT) {
            $return_button = $mform->createElement(
                'button',
                'returnbutton',
                get_string('return', 'local_organization'),
                array('onclick' => 'window.location.href = \'units.php?campus_id=' . $formdata->campus_id . '\';')
            );
        } else {
            $return_button = $mform->createElement(
                'button',
                'returnbutton',
                get_string('return', 'local_organization'),
                array('onclick' => 'window.location.href = \'departments.php?campus_id=' . $formdata->campus_id . '&unit_id=' . $formdata->unit_id . '\';')
            );
        }
        // Conditionally show button groups based on capability .. there might be a better way to do this with just an element but I'm not sure yet
        if (has_capability('local/organization:advisor_edit', $system_context, $USER->id)) {
            // Group the text input and submit button
            $mform->addGroup(array(
                $mform->createElement(
                    'text',
                    'q',
                    get_string('name', 'local_organization')
                ),
                $mform->createElement(
                    'submit',
                    'submitbutton',
                    get_string('filter', 'local_organization')
                ),
                $mform->createElement(
                    'cancel',
                    'resetbutton',
                    get_string('reset', 'local_organization')
                ),
                $mform->createElement(
                    'button',
                    'addadvisor',
                    get_string('new', 'local_organization'),
                    array('onclick' => 'window.location.href = \'edit_advisor.php?campus_id=' . $formdata->campus_id . '&instance_id=' . $formdata->instance_id . '&user_context=' . $formdata->user_context . '&unit_id=' . $formdata->unit_id. '\';')
                ),
                $return_button
            ), 'filtergroup', '', array(' '), false);
        }
        else { // default buttons
            $mform->addGroup(array(
                $mform->createElement(
                    'text',
                    'q',
                    get_string('name', 'local_organization')
                ),
                $mform->createElement(
                    'submit',
                    'submitbutton',
                    get_string('filter', 'local_organization')
                ),
                $mform->createElement(
                    'cancel',
                    'resetbutton',
                    get_string('reset', 'local_organization')
                ),
                $return_button
            ), 'filtergroup', '', array(' '), false);
        }

        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }

}
