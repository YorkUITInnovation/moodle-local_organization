<?php

namespace local_organization;

use local_organization\base;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . "/externallib.php");

class unit_table extends \table_sql
{
    /**
     * unit_table constructor.
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
        global $OUTPUT, $DB, $CFG, $USER;

        // Get number of departments in the unit
        $department_count = $DB->count_records('local_organization_dept', array('unit_id' => $values->id));
        // Get number of advisors in the dept
        $advisor_count = $DB->count_records('local_organization_advisor', array('instance_id' => $values->id, 'user_context' => base::CONTEXT_UNIT));
        // add capabilities for user
        $showEditButtons = false;
        $system_context = \context_system::instance();
        if (has_capability('local/organization:unit_edit', $system_context, $USER->id)) {
            $showEditButtons = true;
        }
        $actions = [
            'edit_url' => $CFG->wwwroot . '/local/organization/edit_unit.php?id=' . $values->id . '&campus_id=' .  $values->campus_id,
            'id' => $values->id,
            'department_count' => $department_count,
            'advisor_count' => $advisor_count,
            'user_context' => base::CONTEXT_UNIT,
            'campus_id' =>  $values->campus_id,
            'showEditButtons' => $showEditButtons
        ];
        return $OUTPUT->render_from_template('local_organization/unit_table_action_buttons', $actions);
    }
}