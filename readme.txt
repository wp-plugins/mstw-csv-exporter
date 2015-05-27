=== MSTW CSV EXPORTER ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: CSV, CSV Export, MSTW, MSTW Plugins, Game Locations, Game Schedules, Game Schedules & Scoreboards 
Requires at least: 3.3.1
Tested up to: 4.2.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Exports various MSTW custom post types to CSV format files.

== Description ==

The MSTW CSV Exporter plugin exports custom post types from the MSTW Game Schedules, MSTW Game Locations, and MSTW Schedules & Scoreboards plugins to CSV format files for import into the MSTW Schedules & Scoreboards plugin, and the MSTW Team Roster (3.2.1) plugin for import into the MTW Team Rosters (4.0 and beyond) plugin. This data export/import is necessary to allow the migration of plugin data across sites.

Version 1.2 adds the ability to export the MSTW Team Rosters (v 3.2.1) data structures - Players and Teams - for import to MSTW Team Rosters 4.0. It allows player photos to be moved from site to site automagically in the import/export process, or not if you are upgrading Team Rosters on a site. See [the Team Rosters CSV Import man page](http://shoalsummitsolutions.com/tr-loading-csv-files/)</a> for more information.

= Helpful Links =
* [**When all else fails try reading the user's manual at shoalsummitsolutions.com -»**](http://shoalsummitsolutions.com/category/csvx-plugin)


== Installation ==

The normal installation methods for WordPress plugins work:

1. Go to the Plugins->Installed plugins screen in Wordpress Admin. Click on Add New. Search for Game Locations. Install it.

2. Download the plugin (.zip file) from WordPress.org. Go to the Plugins->Installed plugins screen in Wordpress Admin. Click on Add New. Click on the Upload link. Find the downloaded .zip file on your computer. Install it.

3. Download the plugin (.zip file) from WordPress.org. Unzip the file. Upload the extracted plugin folder to your website's wp-content/plugins directory using an FTP client or your hosting provider's file manager app. Activate it on the Plugins->Installed plugins screen in WordPress Admin.

== Frequently Asked Questions ==

[The FAQs may be found here.](http://shoalsummitsolutions.com/csvx-faq/)

== Screenshots ==

1. Plugin admin screen

== Upgrade Notice ==
Nothing to note.

== Changelog ==

= 1.2 =
* Added the ability to export the Team Rosters CPT (players) and taxonomy (teams). Also allows player photos to be moved from site to site automagically in the import/export process. See [the Team Rosters CSV Import man page](http://shoalsummitsolutions.com/tr-loading-csv-files/)</a> for more information.
* Updated the MSTW Utility Functions to the latest version.

= 1.1 =
* Added the ability to export MSTW Schedules & Scoreboards CPT's - schedules, games, teams, sports, and venues - including the venue groups for the venues and scoreboards for the games.
* Changed menu position (in includes/mstw-csv-exporter-setup/add_menu_page()) to prevent collision with other MSTW plugins.
* Added MSTW Logo to admin menu entry

= 1.0 =
* Initial release.