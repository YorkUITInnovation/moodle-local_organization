<?php

namespace local_organization;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class unit_filter_form extends moodleform
{
    public function definition()
    {
        $formdata = $this->_customdata['formdata'];
        $mform = $this->_form;


        $context = \context_system::instance();
        // created to return campus_id when submitting/reset/filter
        $mform->addElement(
            'hidden',
            'campus_id'
        );
        $mform->setType(
            'campus_id',
            PARAM_INT
        );

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
                get_string('filter', 'local_organization'),
                array('onclick' => 'window.location.href = \'edit_unit.php?campus_id=' . $formdata->campus_id . '\';')
            ),
            $mform->createElement(
                'cancel',
                'resetbutton',
                get_string('reset', 'local_organization')
            ),
            $mform->createElement(
                'button',
                'addunit',
                get_string('new', 'local_organization'),
                array('onclick' => 'window.location.href = \'edit_unit.php?campus_id=' . $formdata->campus_id . '\';')
            ),
            $mform->createElement(
                'button',
                'campus',
                get_string('campuses', 'local_organization'),
                array('onclick' => 'window.location.href = \'campuses.php\';')
            )
        ), 'filtergroup', '', array(' '), false);
        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }
}