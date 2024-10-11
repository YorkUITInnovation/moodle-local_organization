<?php

namespace local_organization;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once('classes/helper.php');

use local_organization\base;

class department_table extends \table_sql
{

    protected $campus_id; // passed to table since we dont get it table sql
    /**
     * department_table constructor.
     * @param $uniqueid
     */

    public function __construct($uniqueid, $params)
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
        $this->campus_id = $params->campus_id;
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
        global $OUTPUT, $CFG, $DB;
        $advisor_count = $DB->count_records('local_organization_advisor', array('instance_id' => $values->id, 'user_context' => base::CONTEXT_DEPARTMENT));
        $actions = [
            'edit_url' => $CFG->wwwroot . '/local/organization/edit_department.php?id=' . $values->id . '&unit_id=' . $values->unit_id. '&campus_id=' . $this->campus_id,
            'id' => $values->id,
            'advisor_count' => $advisor_count,
            'user_context' => base::CONTEXT_DEPARTMENT,
            'unit_id' => $values->unit_id
        ];

        return $OUTPUT->render_from_template('local_organization/department_table_action_buttons', $actions);
    }
}