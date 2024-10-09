<?php
require_once('../../config.php');
include_once('classes/tables/advisors_table.php');
include_once('classes/forms/advisors_filter_form.php');
include_once('classes/helper.php');


use local_organization\advisors_filter_form;
use local_organization\base;
use local_organization\advisors_table;

global $CFG, $OUTPUT, $PAGE, $DB;


require_login(1, false);

$context = context_system::instance();

// Load AMD module
//$PAGE->requires->js_call_amd('local_organization/campuses', 'init');
// Load CSS file
$PAGE->requires->css('/local/organization/css/general.css');

$id = optional_param('unit_id', '', PARAM_INT);
$user_context = optional_param('user_context', '', PARAM_TEXT);

$formdata = new stdClass();

$mform = new advisors_filter_form(null, array('formdata' => $formdata));

if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present
    // check if UNIT or DEPARTMENT and go to that page
    redirect($CFG->wwwroot . '/local/organization/units.php');
} else if ($data = $mform->get_data()) {
    // Process validated data
    $term_filter = $data->q;
} else {
    // Display the form
   // $mform->display();
}

$table = new advisors_table('local_organization_advisors_table');
$sql = "instance_id != 0";
$params = array('instance_id' => $id, 'user_context' => $user_context);

// Define the SQL query to fetch data
if (!empty($id) && !empty($user_context) ) {
    $fields = "u.firstname,
            u.lastname,
            r.shortname AS role,
            un.shortname AS context";
    $from = '{user} u JOIN {local_organization_advisor} a ON u.id = a.user_id
            JOIN {role} r ON r.id = a.role_id
            JOIN {local_organization_unit} un ON un.id = a.instance_id';
    $conditions = "a.user_context = 'UNIT' and a.id = ".$id;
    //TODO: Parameterize this $id
    debug_to_console($conditions);
    $table->set_sql($fields, $from, $conditions);
}
else {
    $table->set_sql("*", "{local_organization_advisor}", $sql);
}

// Define the base URL for the table
$table->define_baseurl(new moodle_url('/local/organization/advisors.php'));

base::page(
    new moodle_url('/local/organization/advisors.php'),
    get_string('advisors', 'local_organization'),
    get_string('advisors', 'local_organization')
);

echo $OUTPUT->header();
// Set up the table
$mform->display();
$table->out(20, true);
echo $OUTPUT->footer();

