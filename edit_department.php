<?php
require_once("../../config.php");

use local_organization\base;
use local_organization\department;

global $CFG, $DB, $OUTPUT, $PAGE, $USER;

require_once($CFG->dirroot . "/local/organization/classes/forms/department_form.php");

require_login(1, false);

$context = context_system::instance();

$id = optional_param('id', 0, PARAM_INT); // campus id
$unit_id = required_param('unit_id', PARAM_INT); // campus id
// Set page title based on whether we are creating or editing a campus
if ($id) {
    $page_title = get_string('edit_department', 'local_organization');
} else {
    $page_title = get_string('add_department', 'local_organization');
}

// Create campus object
$DEPARTMENT = new department($id);

// Load form data
if ($id != 0) {
    $formdata = $DEPARTMENT->get_record();
} else {
    $formdata = new \stdClass();
    $formdata->id = 0;
}
// Create form
$mform = new \local_organization\department_form(null, array('formdata' => $formdata));
unset($DEPARTMENT);

// Form actions
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/organization/departments.php?unit_id=' . $unit_id);
} else if ($data = $mform->get_data()) {
    $DEPARTMENT = new department($data->id);
    //Handle form submit operation, if form is submitted
    $data->timemodified = time();
    $data->usermodified = $USER->id;

    if ($data->id) {
        $DEPARTMENT->update_record($data);
    } else {
        $data->timecreated = time();
        $DEPARTMENT->insert_record($data);
    }

    redirect($CFG->wwwroot . '/local/organization/departments.php?unit_id=' . $data->unit_id);
} else {
    // Set form data
    $mform->set_data($formdata);
}
// Set page parameters
base::page(
    new moodle_url('/local/organization/edit_department.php?unit_id=' . $unit_id),
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
