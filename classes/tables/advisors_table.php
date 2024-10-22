<?php

namespace local_organization;

use local_organization\base;

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');


class advisors_table extends \table_sql
{
    /**
     * advisors_table constructor.
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
        global $OUTPUT, $DB, $USER;
        // Get number of advisors in unt or departments
        $advisor_count = $DB->count_records('local_organization_advisor', ['instance_id' => $values->instance_id, 'user_context' => $values->user_context]);

        // Capabilities
        $showEditButtons = false;
        $system_context = \context_system::instance();

        // TODO: change this to Patricks base has_capability
        if (has_capability('local/organization:advisor_edit', $system_context, $USER->id)) {
            $showEditButtons = true;
        }
        $actions = [
            //'edit_url' => new \moodle_url('/local/organization/edit_advisor.php', array('id' => $values->id)),
            'id' => $values->id,
            'role_id' => $values->role_id,
            'user_context' => $values->user_context,
            'advisor_count' => $advisor_count,
            'showEditButtons' => $showEditButtons
        ];

        return $OUTPUT->render_from_template('local_organization/advisors_table_action_buttons', $actions);
    }
}