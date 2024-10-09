<?php

require_once($CFG->libdir . "/externallib.php");
require_once("$CFG->dirroot/config.php");

use local_organization\users;

class local_organization_users_ws extends external_api
{
    /**
     * Returns users parameters
     * @return external_function_parameters
     **/

    public static function get_users_parameters() {
        return new external_function_parameters(array('id' => new external_value(PARAM_INT, 'User id', false, -1),'name' => new external_value(PARAM_TEXT, 'User first or last name', false)));
    }

    /** Returns users
     * @global moodle_database $DB
     * @return string users
     **/

    public static function get_users($id, $name="") {
        global $DB;
        $params = self::validate_parameters(self::get_users_parameters(), array('id' => $id,'name' => $name));
        if (strlen($name) >= 3) {
            $sql = "select * from {user} u where ";
            $name = str_replace(' ', '%', $name);
            $sql .= " (Concat(u.firstname, ' ', u.lastname ) like '%$name%' or (u.idnumber like '%$name%') or (u.email like '%$name%') or (u.username like '%$name%'))";
            //How the ajax call with search via the form autocomplete
            $sql .= " Order by u.lastname";
            //How the ajax call with search via the form autocomplete
            $mdlUsers = $DB->get_records_sql($sql, array($name));
        }
        else {
            //            $sql = "select * from {user} Order By lastname"; $mdlUsers = [];
        }
        $users = [];
        $i = 0;
        foreach ($mdlUsers as $u) {
            $users[$i]['id'] = $u->id;
            $users[$i]['firstname'] = $u->firstname;
            $users[$i]['lastname'] = $u->lastname;
            $users[$i]['email'] = $u->email;
            $users[$i]['idnumber'] = $u->idnumber;
            $i++;
        }
        return $users;
    }

    /** Get Users
     * @return single_structure_description
     **/

    public static function user_details() {
        $fields = array(
            'id' => new external_value(PARAM_INT, 'Record id', false),
            'firstname' => new external_value(PARAM_TEXT, 'User first name', true),
            'lastname' => new external_value(PARAM_TEXT, 'User last name', true),
            'email' => new external_value(PARAM_TEXT, 'email', true),
            'idnumber' => new external_value(PARAM_TEXT, 'ID Number', true));
        return new external_single_structure($fields);
    }

    /** Returns users result value
     *  @return external_description
     **/
    public static function get_users_returns() {
        return new external_multiple_structure(self::user_details());
    }

    /**
     * Returns users parameters
     * @return external_function_parameters
     **/

    public static function get_roles_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'User id', false, -1),
                'name' => new external_value(PARAM_TEXT, 'User first or last name', false)
            )
        );
    }

    /** Returns Roles
     * @global moodle_database $DB
     * @return string users
     **/

    public static function get_roles($id, $name="") {
        global $DB;
        $params = self::validate_parameters(
            self::get_users_parameters(),
            array(
                'id' => $id,
                'name' => $name
            )
        );
        if (strlen($name) >= 3) {
            $sql = "select * from {role} u where ";
            $sql .= " (name like '%$name%') OR (shortname like '%$name%')";
            // Get the data
            $existing_roles = $DB->get_records_sql($sql);
        }
        else {
            //            $sql = "select * from {user} Order By lastname"; $mdlUsers = [];
        }
        $roles = [];
        $i = 0;
        foreach ($existing_roles as $r) {
            $roles[$i]['id'] = $r->id;
            // System roles have no name
            if (empty($r->name)) {
                switch($r->shortname) {
                    case 'editingteacher':
                    case 'teacher':
                        $roles[$i]['name'] = get_string('legacy:' . $r->shortname, 'core_role');
                        break;
                    default:
                        $roles[$i]['name'] = get_string($r->shortname, 'core_role');
                        break;
                }

            }
            else {
                $roles[$i]['name'] = $r->name;
            }
            $i++;
        }
        return $roles;
    }

    /** Get Users
     * @return single_structure_description
     **/

    public static function roles_details() {
        $fields = array(
            'id' => new external_value(PARAM_INT, 'Record id', false),
            'name' => new external_value(PARAM_TEXT, 'User first name', true)
        );
        return new external_single_structure($fields);
    }

    /** Returns users result value
     *  @return external_description
     **/
    public static function get_roles_returns() {
        return new external_multiple_structure(self::roles_details());
    }
}