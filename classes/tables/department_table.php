<?php

namespace local_organization;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');

class department_table extends \table_sql
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
        global $OUTPUT, $CFG;
        $actions = [
            'edit_url' => $CFG->wwwroot . '/local/organization/edit_department.php?id=' . $values->id . '&unit_id=' . $values->unit_id,
            'id' => $values->id
        ];

        return $OUTPUT->render_from_template('local_organization/department_table_action_buttons', $actions);
    }
}