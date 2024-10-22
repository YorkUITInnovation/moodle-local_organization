<?php

namespace local_organization;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class department_filter_form extends moodleform
{
    public function definition()
    {

        GLOBAL $USER;
        $formdata = $this->_customdata['formdata'];
        $mform = $this->_form;

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
            'unit_id'
        );
        $mform->setType(
            'unit_id',
            PARAM_INT
        );


        // Conditionally show button groups based on capability .. there might be a better way to do this with just an element but I'm not sure yet
        $system_context = \context_system::instance();
        if (has_capability('local/organization:unit_edit', $system_context, $USER->id)) {
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
                    'adddepartment',
                    get_string('new', 'local_organization'),
                    array('onclick' => 'window.location.href = \'edit_department.php?unit_id=' . $formdata->unit_id . '&campus_id=' . $formdata->campus_id . '\';')
                ),
                $mform->createElement(
                    'button',
                    'unitsretunr',
                    get_string('units', 'local_organization'),
                    array('onclick' => 'window.location.href = \'units.php?campus_id=' . $formdata->campus_id . '\';')
                )
            ), 'filtergroup', '', array(' '), false);
        }
        else {
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
                    'unitsretunr',
                    get_string('units', 'local_organization'),
                    array('onclick' => 'window.location.href = \'units.php?campus_id=' . $formdata->campus_id . '\';')
                )
            ), 'filtergroup', '', array(' '), false);
        }
        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }
}