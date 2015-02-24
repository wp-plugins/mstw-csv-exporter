=== MSTW CSV EXPORTER ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: CSV, CSV Export, MSTW, MSTW Plugins, Game Locations, Game Schedules, Game Schedules & Scoreboards 
Requires at least: 3.3.1
Tested up to: 4.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Exports various MSTW custom post types to CSV format files.

== Description ==

The MSTW CSV Exporter plugin exports custom post types from the MSTW Game Schedules, MSTW Game Locations, and MSTW Schedules & Scoreboards plugins to CSV format files for import into the MSTW Schedules & Scoreboards plugin. This allows the migration of plugin data across sites.

Version 1.0 supported migration of the MSTW Game Locations and MSTW Game Schedules plugins to the new MSTW Schedules & Scoreboards plugin. The old plugins have been integrated into the new plugin, which changed the data structures (in the custom post types) to support more robust data migration across sites running the Schedules & Scoreboards plugin.

Version 1.1 added the ability to export the new MSTW Schedules & Scoreboards plugin data tables - Games, Teams, Schedules, Sports, and Venues - AND the associated Venue Group and Scoreboard taxonomies, for import to another site.

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
* Added the ability to export the Team Rosters CPT (players) and taxonomy (teams)

= 1.1 =
* Added the ability to export MSTW Schedules & Scoreboards CPT's - schedules, games, teams, sports, and venues - including the venue groups for the venues and scoreboards for the games.
* Changed menu position (in includes/mstw-csv-exporter-setup/add_menu_page()) to prevent collision with other MSTW plugins.
* Added MSTW Logo to admin menu entry

= 1.0 =
* Initial release.