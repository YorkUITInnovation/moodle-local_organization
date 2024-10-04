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
                array('onclick' => 'window.location.href = \'edit_advisor.php\';')
            )
        ), 'filtergroup', '', array(' '), false);
        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }

}
