<?php
require_once('../../config.php');
include_once('classes/tables/unit_table.php');
include_once('classes/forms/unit_filter_form.php');

use local_organization\base;
use local_organization\unit_table;
use local_organization\unit_filter_form;

global $CFG, $OUTPUT, $PAGE, $DB;


require_login(1, false);

$context = context_system::instance();

// Load AMD module
$PAGE->requires->js_call_amd('local_organization/units', 'init');
// Load CSS file
$PAGE->requires->css('/local/organization/css/general.css');

$term = optional_param('q', '', PARAM_TEXT);

$formdata = new stdClass();
$formdata->name = $term;

$mform = new unit_filter_form(null, array('formdata' => $formdata));

if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present
    redirect($CFG->wwwroot . '/local/organization/units.php');
} else if ($data = $mform->get_data()) {
    // Process validated data
    $term_filter = $data->q;
} else {
    // Display the form
//    $mform->display();
}

$table = new unit_table('local_organization_units_table');
$params = array();
// Define the SQL query to fetch data
$sql = "id != 0";
if (!empty($term_filter)) {
    $sql .= " AND (name LIKE '%$term_filter%') OR (shortname LIKE '%$term_filter%')";
}

// Define the SQL query to fetch data
$table->set_sql('*', '{local_organization_unit}', $sql);

// Define the base URL for the table
$table->define_baseurl(new moodle_url('/local/organization/units.php'));

base::page(
    new moodle_url('/local/organization/units.php'),
    get_string('units', 'local_organization'),
    get_string('units', 'local_organization')
);

echo $OUTPUT->header();
// Set up the table
$mform->display();
$table->out(20, true);
echo $OUTPUT->footer();

