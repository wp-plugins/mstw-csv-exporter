=== MSTW CSV EXPORTER ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: CSV, CSV Export, MSTW, MSTW Plugins, Game Locations, MSTW Game Locations, Game Schedules, MSTW Game Schedules, MSTW Schedules & Scoreboards, Team Rosters, MSTW Team Rosters 
Requires at least: 3.3.1
Tested up to: 4.2.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Exports MSTW custom data types to CSV format files for backup, upgrade, and migration across installs.

== Description ==

The MSTW CSV Exporter plugin exports all data types from the MSTW Game Schedules, MSTW Game Locations, and MSTW Schedules & Scoreboards plugins to CSV format files for import into the MSTW Schedules & Scoreboards v4.0 plugin, and the MSTW Team Roster (v3.2.1) plugin for import into the MSTW Team Rosters (v4.0) plugin. This data export/import is necessary to allow the migration of plugin data across sites. The plugin also exports MSTW Schedules & Scoreboards v4.0 and MSTW Team Rosters v4.0 data.

Version 1.2 adds the ability to export the MSTW Team Rosters (v3.2.1) data structures - Players and Teams - for import to MSTW Team Rosters v4.0. It allows player photos to be moved from site to site automagically in the import/export process (or not if you are upgrading Team Rosters on one site). See [the Team Rosters CSV Import man page](http://shoalsummitsolutions.com/tr-loading-csv-files/)</a> for more information.

= Helpful Links =
* [**When all else fails try reading the user's manual at shoalsummitsolutions.com -»**](http://shoalsummitsolutions.com/category/csvx-plugin)

== Installation ==

[Complete installation instructions are available on shoalsummitsolutions.com](http://shoalsummitsolutions.com/csvx-installation/).

== Frequently Asked Questions ==

[The FAQs may be found on shoalsummitsolutions.com](http://shoalsummitsolutions.com/csvx-faq/).

== Screenshots ==

1. Plugin admin screen

== Upgrade Notice ==
Nothing to note.

== Changelog ==

= 1.2 =
* Added the ability to export the Team Rosters (v3.1.2) CPT (players) and taxonomy (teams). Also allows player photos to be moved from site to site automagically in the import/export process. See [the Team Rosters CSV Import man page](http://shoalsummitsolutions.com/loading-rosters-from-csv-files-v-4-0/)</a> for more information.
* Added the ability to export Team Rosters (v4.0) data structures (as above). 
* Updated the MSTW Utility Functions to the latest version (to avoid installation collisions).

= 1.1 =
* Added the ability to export MSTW Schedules & Scoreboards CPT's - schedules, games, teams, sports, and venues - including the venue groups for the venues and scoreboards for the games.
* Changed menu position (in includes/mstw-csv-exporter-setup/add_menu_page()) to prevent collision with other MSTW plugins.
* Added MSTW Logo to admin menu entry

= 1.0 =
* Initial release.