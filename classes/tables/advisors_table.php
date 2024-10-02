<?php

namespace local_organization;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');

class advisors_table extends \table_sql
{
    /**
     * campus_table constructor.
     * @param $uniqueid
     */
    public function __construct($uniqueid)
    {
        parent::__construct($uniqueid);

        // Define the columns to be displayed
        $columns = array('first_name', 'last_name', 'actions');
        $this->define_columns($columns);

        // Define the headers for the columns
        $headers = array(
            get_string('firstname', 'local_organization'),
            get_string('lastname', 'local_organization'),
            get_string('context', 'local_organization'),
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
        // Get number of advisors in unt or departments

        $condition1 = 'instance_id = :param1'; // DEPARTMENT or UNIT id
        $condition2 = 'user_context = :param2';

        $params = array(
            'instanceid' => $values->instance_id,
            'usercontext' => $values->user_context,
        );

        $advisor_count = $DB->count_records('local_organization_advisor', $condition1, $condition2);
        $actions = [
            'edit_url' => new \moodle_url('/local/organization/edit_campus.php', array('id' => $values->id)),
            'id' => $values->id,
            'unit_count' => $advisor_count
        ];

        return $OUTPUT->render_from_template('local_organization/unit_table_action_buttons', $actions);
    }
}