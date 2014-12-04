=== MSTW CSV EXPORTER ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: CSV, CSV Export, MSTW, MSTW Plugins, Game Locations, Game Schedules, Game Schedules & Scoreboards 
Requires at least: 3.3.1
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Exports CPTs in the MSTW Game Schedules and Game Locations plugins to CSV format files.

== Description ==

The MSTW CSV Exporter plugin exports custom post types from the MSTW Game Schedules and MSTW Game Locations plugins to CSV format files for import into the MSTW Schedules & Scoreboards plugin. 

Its primary purpose to to support data migration when upgrading from the MSTW Game Locations and MSTW Game Schedules plugins to the new MSTW Schedules & Scoreboards plugin. The old plugins have been integrated into the new plugin which changed the data structures (in the custom post types) to support more robust data migration across sites running the Schedules & Scoreboards plugin.

VERSION 1.0 IS OF VALUE ONLY FOR MIGRATING SITES USING MSTW GAME LOCATIONS AND GAME SCHEDULES TO THE NEW MSTW SCHEDULES & SCOREBOARDS PLUGIN.

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

This is the initial release.

== Changelog ==

= 1.1 =
* Changed menu position (in includes/mstw-csv-exporter-setup/add_menu_page()) to prevent collision with other MSTW plugins.

= 1.0 =
* Initial release.