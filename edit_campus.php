<?php
require_once("../../config.php");

use local_organization\base;
use local_organization\campus;

global $CFG, $DB, $OUTPUT, $PAGE, $USER;

require_once($CFG->dirroot . "/local/organization/classes/forms/campus_form.php");

require_login(1, false);

$context = context_system::instance();

$id = optional_param('id', 0, PARAM_INT); // campus id
// Set page title based on whether we are creating or editing a campus
if ($id) {
    $page_title = get_string('edit_campus', 'local_organization');
} else {
    $page_title = get_string('add_campus', 'local_organization');
}

// Create campus object
$CAMPUS = new campus($id);

// Load form data
if ($id != 0) {
    $formdata = $CAMPUS->get_record();
} else {
    $formdata = new \stdClass();
    $formdata->id = 0;
}
// Create form
$mform = new \local_organization\campus_form(null, array('formdata' => $formdata));
unset($CAMPUS);

// Form actions
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/organization/campuses.php');
} else if ($data = $mform->get_data()) {
    $CAMPUS = new campus($data->id);
    //Handle form submit operation, if form is submitted
    $data->timemodified = time();
    $data->usermodified = $USER->id;

    if ($data->id) {
        $CAMPUS->update_record($data);
    } else {
        $data->timecreated = time();
        $CAMPUS->insert_record($data);
    }

    redirect($CFG->wwwroot . '/local/organization/campuses.php');
} else {
    // Set form data
    $mform->set_data($formdata);
}
// Set page parameters
base::page(
    new moodle_url('/local/organization/edit_campus.php'),
    $page_title,
    $page_title,
    $context
);

// Output header
echo $OUTPUT->header();

// Display form
$mform->display();

// Output footer
echo $OUTPUT->footer();
