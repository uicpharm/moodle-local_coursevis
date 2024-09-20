<?php

namespace local_coursevis\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;

class update_courses extends scheduled_task
{
   public function get_name()
   {
      return get_string('taskname', 'local_coursevis');
   }

   public function execute()
   {
      global $DB;

      // Plugin configs
      $datetimeMask = 'n/j/y g:ia';
      $categories = get_config('local_coursevis', 'categories');

      // Plugin config validation
      if (empty($categories)) {
         mtrace(
            'Aborting since your plugin configurations are incomplete. ' .
            'This task will still report as successful so that it does not run again until its normally scheduled time. '
         );
         return;
      }

      // Find category IDs from the `idnumber` values
      $categoryRecords = $DB->get_records_select('course_categories', 'find_in_set(idnumber, :categories)', ['categories' => $categories]);
      $categoryIds = implode(',', array_column($categoryRecords, 'id'));

      mtrace("Using these configurations. Categories: $categories ($categoryIds).");

      // Find courses that need to be updated
      // - Filter courses by categories that match our config, or sub-categories of those (by looking at the sub-category's `path`)
      // - Filter by records that have a `visible` status different than what it should be
      $courses = $DB->get_records_sql("
         select crs.id, if(startdate<UNIX_TIMESTAMP() and enddate>UNIX_TIMESTAMP(), 1, 0) new_visible
         from {course} crs
         join {course_categories} cat on crs.category=cat.id
         where
            crs.visible <> if(startdate<UNIX_TIMESTAMP() and enddate>UNIX_TIMESTAMP(), 1, 0) and
            (
               find_in_set(cat.id, ?) -- Is a top-level match
               or
               find_in_set(substring(substring_index(cat.path, '/', 2), 2), ?) -- Is a sub-category of a match
            )
         order by crs.id
      ", [$categoryIds, $categoryIds]);

      mtrace('There are ' . count($courses) . ' course(s) that need to be updated.');

      foreach ($courses as $rec) {
         $course = get_course($rec->id);
         $start = date($datetimeMask, $course->startdate);
         $end = date($datetimeMask, $course->enddate);
         $vis = $rec->new_visible ? 'visible' : 'hidden';
         mtrace("#$course->id \"$course->fullname\" ($start - $end) set to $vis.");
         $course->visibleold = $course->visible;
         $course->visible = $rec->new_visible;
         $DB->update_record('course', $course);
      }
   }
}
