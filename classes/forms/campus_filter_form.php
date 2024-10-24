<?php
require_once("$CFG->libdir/formslib.php");

class campus_filter_form extends moodleform {
    public function definition() {

        global $USER;
        $formdata = $this->_customdata['formdata'];
        $mform = $this->_form;

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
                    'addcampus',
                    get_string('new', 'local_organization'),
                    array('onclick' => 'window.location.href = \'edit_campus.php\';')
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
                )
            ), 'filtergroup', '', array(' '), false);
        }
        $mform->setType('q', PARAM_NOTAGS);

        $this->set_data($formdata);
    }
}