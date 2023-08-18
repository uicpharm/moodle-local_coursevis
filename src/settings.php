<?php
defined('MOODLE_INTERNAL') || die;

$settings = new admin_settingpage('local_coursevis', get_string('pluginname', 'local_coursevis'));

$settings->add(new admin_setting_configtext(
   'local_coursevis/categories',
   get_string('categories', 'local_coursevis'),
   get_string('categories_desc', 'local_coursevis'),
   '',
   PARAM_TEXT
));

$ADMIN->add('localplugins', $settings);
