<?php
require_once("../../config.php");

use local_organization\base;
use local_organization\advisor;
use local_organization\advisors_form;

global $CFG, $DB, $OUTPUT, $PAGE, $USER;

include_once('classes/helper.php');
require_once($CFG->dirroot . "/local/organization/classes/forms/advisors_form.php");

require_login(1, false);

$context = context_system::instance();

$id = optional_param('id', 0, PARAM_INT); // user
$instance_id = optional_param('instance_id', 0, PARAM_INT); // unit_id
// get user context of user UNIT or DEPARTMENT
$user_context = optional_param('user_context', '', PARAM_TEXT); // user



// Set page title based on whether we are creating or editing a campus
$page_title = get_string('add_advisor', 'local_organization');

// Create advisor object
$ADVISOR = new advisor($id);

// Load form data
// THERE is no edit advisor for now
if ($id != 0) {
    $formdata = $ADVISOR->get_record();
} else {
    $formdata = new \stdClass();
    $formdata->id = 0;
    $formdata->instance_id = $instance_id;
    $formdata->user_context = $user_context;
}
// Create form
$mform = new advisors_form(null, array('formdata' => $formdata));
unset($ADVISOR);

// Form actions
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/organization/advisors.php?instance_id=' . $instance_id . '&user_context=' .$user_context);
} else if ($data = $mform->get_data()) {

    $ADVISOR = new advisor($data->id);
    if ($data->id == 0) {
        // is $data->user_id an array
        if (is_array($data->user_id)) {
            // insert multiple records
            foreach ($data->user_id as $user_id) {
                $data->user_id = $user_id;
                $ADVISOR->insert_record($data);
            }
        } else {
            $ADVISOR->insert_record($data);
        }
    } else {
        $ADVISOR->update_record($data);
    }
    debug_to_console("After update or insert");
    debug_to_console($data);
    if ($user_context == 'UNIT') {
        redirect($CFG->wwwroot . '/local/organization/advisors.php?unit_id=' . $data->unit_id . '&nstance_id=' . $data->instance_id .'&user_context=' . $data->user_context);
    }
    else {
        redirect($CFG->wwwroot . '/local/organization/advisors.php?instance_id=' . $data->instance_id . '&user_context=' . $data->user_context);
    }
} else {
    // Set form data
    $mform->set_data($formdata);
}
debug_to_console('no redirect');
// Set page parameters

base::page(
    new moodle_url('/local/organization/advisors.php'),
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
