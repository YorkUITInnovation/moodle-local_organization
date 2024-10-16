<?php
require_once("../../config.php");

use local_organization\base;
use local_organization\unit;

global $CFG, $DB, $OUTPUT, $PAGE, $USER;

require_once($CFG->dirroot . "/local/organization/classes/forms/unit_form.php");

require_login(1, false);

$context = context_system::instance();
// Capability to view/edit page
$hasCapability_view_edit = has_capability('local/organization:unit_view', $PAGE->context, $USER->id) && has_capability('local/organization:unit_edit', $PAGE->context, $USER->id) ;
if (!$hasCapability_view_edit) {
    redirect($CFG->wwwroot . '/my');
}
$id = optional_param('id', 0, PARAM_INT); // unit id
$campus_id = required_param('campus_id', PARAM_INT); // campus id
// Set page title based on whether we are creating or editing a campus
if ($id) {
    $page_title = get_string('edit_unit', 'local_organization');
} else {
    $page_title = get_string('add_unit', 'local_organization');
}

// Create campus object
$UNIT = new unit($id);

// Load form data
if ($id != 0) {
    $formdata = $UNIT->get_record();
} else {
    $formdata = new \stdClass();
    $formdata->id = 0;
    $formdata->campus_id = $campus_id;
}
// Create form
$mform = new \local_organization\unit_form(null, array('formdata' => $formdata));
unset($UNIT);

// Form actions
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/organization/units.php?campus_id=' . $campus_id);
} else if ($data = $mform->get_data()) {
    $UNIT = new unit($data->id);
    //Handle form submit operation, if form is submitted
    $data->timemodified = time();
    $data->usermodified = $USER->id;

    if ($data->id) {
        $UNIT->update_record($data);
    } else {
        $data->timecreated = time();
        $UNIT->insert_record($data);
    }

    redirect($CFG->wwwroot . '/local/organization/units.php?campus_id=' . $data->campus_id);
} else {
    // Set form data
    $mform->set_data($formdata);
}
// Set page parameters
base::page(
    new moodle_url('/local/organization/edit_unit.php?campus_id=' . $campus_id),
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
