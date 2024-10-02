<?php

namespace local_organization;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');

class campus_table extends \table_sql
{
    /**
     * campus_table constructor.
     * @param $uniqueid
     */
    public function __construct($uniqueid)
    {
        parent::__construct($uniqueid);

        // Define the columns to be displayed
        $columns = array('name', 'shortname', 'actions');
        $this->define_columns($columns);

        // Define the headers for the columns
        $headers = array(
            get_string('name', 'local_organization'),
            get_string('short_name', 'local_organization'),
            '',
        );

        $this->define_headers($headers);
    }

    /**
     * Function to define the actions column
     *
     * @param $values
     * @return string
     */
    public function col_actions($values)
    {
        global $OUTPUT, $DB;
        // Get number of units in the campus
        $unit_count = $DB->count_records('local_organization_unit', array('campus_id' => $values->id));
        $actions = [
            'edit_url' => new \moodle_url('/local/organization/edit_campus.php', array('id' => $values->id)),
            'id' => $values->id,
            'unit_count' => $unit_count
        ];

        return $OUTPUT->render_from_template('local_organization/campus_table_action_buttons', $actions);
    }
}