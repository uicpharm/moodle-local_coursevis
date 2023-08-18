<?php
defined('MOODLE_INTERNAL') || die();

$tasks = [
   [
      'classname' => 'local_coursevis\task\update_courses', // Task class namespace.
      'blocking' => 0, // Whether this task is blocking (1) or not (0).
      'minute' => '1',
      'hour' => '*',
      'day' => '*',
      'dayofweek' => '*',
      'month' => '*',
   ],
];
