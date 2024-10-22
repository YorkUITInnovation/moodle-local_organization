<?php
/*
 * Author: Admin User
 * Create Date: 28-09-2024
 * License: LGPL 
 * 
 */

namespace local_organization;
use local_organization\base;
class advisors
{

    /**
     *
     * @var string
     */
    private $results;

    /**
     *
     * @global \moodle_database $DB
     */
    public function __construct()
    {
        global $DB;
        $this->results = $DB->get_records('local_organization_advisor');
    }

    /**
     * Get records
     */
    public function get_records()
    {
        return $this->results;
    }

    /**
     * Array to be used for selects
     * Defaults used key = record id, value = name
     * Modify as required.
     */
    public function get_select_array()
    {
        foreach ($this->results as $r) {
            $array[$r->id] = $r->name;
        }
        return $array;
    }

    /**
     * Get user advisor assignments
     *
     * @return array
     */
    public function get_user_advisor_assignments($userid = null) {
        global $USER, $DB;
        if (is_null($userid)) {
            $userid = $USER->id;
        }
        $results = $DB->get_records_sql("SELECT * FROM {local_organization_advisor} WHERE user_id = ?", array($USER->id));
        return $results;
    }
}
