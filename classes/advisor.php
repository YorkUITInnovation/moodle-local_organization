<?php
/*
 * Author: Admin User
 * Create Date: 28-09-2024
 * License: LGPL 
 * 
 */

namespace local_organization;

use local_organization\base;
use local_organization\crud;
class advisor extends crud
{


    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var int
     */
    private $user_id;

    /**
     *
     * @var int
     */
    private $role_id;

    /**
     *
     * @var int
     */
    private $instance_id;

    /**
     *
     * @var string
     */
    private $user_context;

    /**
     *
     * @var int
     */
    private $usermodified;

    /**
     *
     * @var int
     */
    private $timecreated;

    /**
     *
     * @var string
     */
    private $timecreated_hr;

    /**
     *
     * @var int
     */
    private $timemodified;

    /**
     *
     * @var string
     */
    private $timemodified_hr;

    /**
     *
     * @var string
     */
    private $table;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_organization_advisor';

        parent::set_table($this->table);

        if ($id) {
            $this->id = $id;
            parent::set_id($this->id);
            $result = $this->get_record($this->table, $this->id);
        } else {
            $result = new \stdClass();
            $this->id = 0;
            parent::set_id($this->id);
        }

        $this->user_id = $result->user_id ?? 0;
        $this->role_id = $result->role_id ?? 0;
        $this->instance_id = $result->instance_id ?? 0;
        $this->user_context = $result->user_context ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = userdate($result->timecreated,get_string('strftimedate'));
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = userdate($result->timemodified,get_string('strftimedate'));
        }
    }

    /**
     * @return id - bigint (18)
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return user_id - bigint (18)
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * @return role_id - bigint (18)
     */
    public function get_roleid()
    {
        return $this->role_id;
    }

    /**
     * @return instance_id - bigint (18)
     */
    public function get_instanceid()
    {
        return $this->instance_id;
    }

    /**
     * @return user_context - varchar (255)
     */
    public function get_usercontext()
    {
        return $this->user_context;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_usermodified()
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (18)
     */
    public function get_timecreated()
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (18)
     */
    public function get_timemodified()
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_roleid($role_id)
    {
        $this->role_id = $role_id;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_instanceid($instance_id)
    {
        $this->instance_id = $instance_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_usercontext($user_context)
    {
        $this->user_context = $user_context;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_usermodified($usermodified)
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timecreated($timecreated)
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timemodified($timemodified)
    {
        $this->timemodified = $timemodified;
    }

    /**
     * Insert record into selected table
     * @param object $data
     * @global \stdClass $USER
     * @global \moodle_database $DB
     */
    public function insert_record($data)
    {
        global $DB, $USER;

        $context = \context_system::instance();

        if (!isset($data->timecreated)) {
            $data->timecreated = time();
        }

        if (!isset($data->timemodified)) {
            $data->timemodified = time();
        }

        //Set user
        $data->usermodified = $USER->id;

        $id = $DB->insert_record($this->table, $data);

        // Now add user to the role
        role_assign($data->role_id, $data->user_id, $context->id);

        return $id;
    }

}