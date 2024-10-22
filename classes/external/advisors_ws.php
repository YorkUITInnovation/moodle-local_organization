<?php

require_once($CFG->libdir . "/externallib.php");
require_once("$CFG->dirroot/config.php");

use local_organization\advisor;

class local_organization_advisors_ws extends external_api
{
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Advisor ID', false, 0),
                'role_id' => new external_value(PARAM_TEXT, 'Role Id', false, ""),
                'context' => new external_value(PARAM_TEXT, 'User Context', false, "")
            )
        );
    }

    /**
     * @param $id
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function delete($id, $role, $context)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::delete_parameters(), array(
                'id' => $id,
                'role_id' => $role,
                'context' => $context
            )
        );
        $data= new stdClass();
        $data->id = $params['id'];
        $data->role_id = $params['role_id'];
        $data->context = $params['context'];

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);
        $ADVISOR = new advisor($id);
        // can get user id, instance from $ADVISOR to check roles/capabilities
        $data->user_id = $ADVISOR->get_user_id();
        $data->instance_id = $ADVISOR->get_instanceid();
        // Also Delete role for user
        $deleted = $ADVISOR->delete_advisor_role_record($data);
        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns()
    {
        return new external_value(PARAM_INT, 'Boolean');
    }
}