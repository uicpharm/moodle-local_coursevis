---
# https://vitepress.dev/reference/default-theme-home-page
layout: home

hero:
   name: 'Moodle Plugin:'
   text: Course Visibility
   tagline: Automate showing/hiding courses based on start/end times
---

# What is it?

This is a simple plugin that runs as a scheduled task. It reviews all courses in the
categories you specify in the settings, adjusting the course visibility to hidden or
visible based on whether we are currently within the start/end date/time of the course.

By default, the task will run hourly at 1 minute past the hour.

## Categories

The `local_coursevis | categories` setting must contain a value for the scheduled task to
run. If the setting isn't set to some value, the scheduled task will abort.

The categories should be a comma-delimited list of category "ID numbers". We use the ID
number to facilitate custom identification of categories. All subcategories under a
targeted category will be reviewed.

## Installation

Download the plugin from the
[releases](https://github.com/uicpharm/moodle-local_coursevis/releases) page and install
it using the Moodle admin interface.

It has no custom tables. It just sets up a scheduled task called
`local_coursevis\task\update_courses`. On the initial installation, if it detects that the
required category setting hasn't been set yet, it will abort until configuration is
complete.
