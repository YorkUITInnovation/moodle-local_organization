<?php

$functions = array(
    'organization_campus_delete' => array(
        'classname' => 'local_organization_campus_ws',
        'methodname' => 'delete',
        'classpath' => 'local/organization/classes/external/campus_ws.php',
        'description' => 'Delete campus record. It will only delete if no units are associated',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'organization_unit_delete' => array(
        'classname' => 'local_organization_unit_ws',
        'methodname' => 'delete',
        'classpath' => 'local/organization/classes/external/unit_ws.php',
        'description' => 'Delete unit record. Will only delete if no departments are associated',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
);