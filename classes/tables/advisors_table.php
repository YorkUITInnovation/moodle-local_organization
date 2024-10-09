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
        $columns = array('firstname', 'lastname', 'role', 'context', 'actions');
        $this->define_columns($columns);

        // Define the headers for the columns
        $headers = array(
            get_string('firstname', 'local_organization'),
            get_string('lastname', 'local_organization'),
            get_string('role', 'local_organization'),
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

        $advisor_count = $DB->count_records('local_organization_advisor', ['instance_id' => $values->instance_id, 'user_context' => $values->user_context]);
        $actions = [
            'edit_url' => new \moodle_url('/local/organization/edit_advisor.php', array('id' => $values->id)),
            'id' => $values->id,
            'advisor_count' => $advisor_count,
        ];

        return $OUTPUT->render_from_template('local_organization/advisors_table_action_buttons', $actions);
    }
}