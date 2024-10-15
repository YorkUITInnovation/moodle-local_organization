<?php

namespace local_organization;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class advisors_filter_form extends \moodleform
{
    public function definition()
    {

        $formdata = $this->_customdata['formdata'];
        $mform = $this->_form;

        // Define no submit button
//        $mform->registerNoSubmitButton('resetbutton');
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
                array('onclick' => 'window.location.href = \'departments.php?campus_id=' . $formdata->campus_id . '&unit_id='. $formdata->unit_id. '\';')
            );
        }

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
        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }

}
