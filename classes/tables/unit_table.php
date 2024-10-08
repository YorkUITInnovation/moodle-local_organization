<?php

namespace local_organization;

use local_organization\base;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');

class unit_table extends \table_sql
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
        global $OUTPUT, $DB, $CFG;

        // Get number of departments in the unit
        $department_count = $DB->count_records('local_organization_dept', array('unit_id' => $values->id));
        $advisor_count = $DB->count_records('local_organization_advisor', array('instance_id' => $values->id, 'user_context' => base::CONTEXT_UNIT));
        echo("<script>console.log('PHP: " . $values->id . "');</script>");
        $actions = [
            'edit_url' => $CFG->wwwroot . '/local/organization/edit_unit.php?id=' . $values->id . '&campus_id=' .  $values->campus_id,
            'id' => $values->id,
            'department_count' => $department_count,
            'advisor_count' => $advisor_count,
            'user_context' => base::CONTEXT_UNIT,
            'campus_id' =>  $values->campus_id
        ];

        return $OUTPUT->render_from_template('local_organization/unit_table_action_buttons', $actions);
    }
}