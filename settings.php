<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('organization', get_string('pluginname', 'local_organization')));

    $settings = new admin_externalpage('local_organization_settings',
        get_string('manageorg', 'local_organization', null, true),
        new moodle_url('/local/organization/campuses.php'));

    $ADMIN->add('organization', $settings);

    $settings = null;
}