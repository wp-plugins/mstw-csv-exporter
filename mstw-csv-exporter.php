<?php
/*
Plugin Name: MSTW CSV Exporter
Plugin URI: http://wordpress.org/extend/plugins/
Description: Exports custom post types in the MSTW Game Schedules and MSTW Game Locations plugins to CSV format files for import into the MSTW Schedules & Scoreboards plugin.
Version: 1.2
Author: Mark O'Donnell
Author URI: http://shoalsummitsolutions.com
Text Domain: mstw-csv-exporter
*/

/*------------------------------------------------------------------------------
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *--------------------------------------------------------------------------*/
 
/*------------------------------------------------------------------------------
 *	A portion of this plugin is based on the Custom CSV Export plugin
 *	(http://wordpress.org/plugins/custom-csv-exporter/) by mburris 
 *	(http://wrapping.marthaburtis.net). All rights flow through under 
 *  the GNU General Public License.
 *--------------------------------------------------------------------------*/
 
//------------------------------------------------------------------------
// DEFINE SOME GLOBALS TO MAKE LIFE EASIER this is now 'standard' MSTW stuff
//
include_once( WP_PLUGIN_DIR . '/mstw-csv-exporter/includes/mstw-csvx-globals.php' );

register_activation_hook( __FILE__, array('MSTW_CSVX', 'mstw' ) );
register_deactivation_hook( __FILE__, array('MSTW_CSVX', 'deactivate' ) );

// ----------------------------------------------------------------
// Set up localization
//
add_action( 'plugins_loaded', 'mstw_csvx_load_localization' );
	
function mstw_csvx_load_localization( ) {
	load_plugin_textdomain( 'mstw-csv-exporter', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

function debug_load_textdomain( $domain, $mofile ) {
	//mstw_log_msg( "Trying textdomain ". $domain . " at filename " . $mofile );
}
//add_action('load_textdomain', 'debug_load_textdomain'); 
 
//----------------------------------------------------------------	
// Enqueue the script for the CPT type selection (radio)
//
add_action( 'admin_enqueue_scripts', 'mstw_csvx_admin_enqueue_scripts' );

function mstw_csvx_admin_enqueue_scripts( $hook_suffix ) {
 
 	wp_enqueue_script( 'csv-cpt-type', MSTW_CSVX_JS_URL . '/ss-csv-cpt-type.js', array( 'jquery' ), false, true );
	//mstw_log_msg( 'enqueueing js: ' . MSTW_CSVX_JS_URL . '/ss-csv-cpt-type.js' );
	wp_enqueue_style( 'csvx-style', plugin_dir_url( __FILE__ ) . 'css/mstw-csvx-styles.css', array(), false, 'all' );
	//mstw_log_msg( 'in mstw_csvx_admin_enqueue_scripts stylesheet = ' . plugin_dir_url( __FILE__ ) . 'css/mstw-csvx-styles.css' );
	
}

//
// load the admin utils for convenience
// we're in wp-admin
//if (is_admin() ) {
	//require_once ( dirname( __FILE__ ) . '/includes/mstw-utility-functions.php' );
	require_once ( MSTW_CSVX_INCLUDES_DIR . '/mstw-utility-functions.php' );
	require_once ( dirname( __FILE__ ) . '/includes/mstw-csv-exporter-setup.php' );
	require_once ( dirname( __FILE__ ) . '/includes/mstw-csv-exporter-fcns.php' );
//}

// ----------------------------------------------------------------
// CSV Exporter stuff
//
//add_action( 'admin_init', 'mstw_gl_init_export' );
//function mstw_gl_init_export( ) {
	if( !class_exists( 'MSTW_CSVX' ) ) {
		class MSTW_CSVX {
			/**
			 * Construct the plugin object
			 */
			public function __construct( ) {
				// Initialize Settings
				require_once( sprintf( "%s/includes/mstw-csv-exporter-setup.php", dirname( __FILE__ ) ) );
				require_once( sprintf( "%s/includes/mstw-csv-exporter-fcns.php", dirname( __FILE__ ) ) );
				add_action( 'init', 'mstw_csvx_export', 12 );
				$MSTW_CSVX_Settings = new MSTW_CSVX_Settings();
				
			} // END public function __construct
			
			//
			// Activate the plugin
			//
			public static function activate( ) {
				// Do nothing
			} // END public static function activate
		
			//
			// Deactivate the plugin
			//		
			public static function deactivate() {
				
			} 
		} //End: class MSTW_CSVX
	} //End: if( !class_exists( 'MSTW_CSVX' ) )

	if( class_exists( 'MSTW_CSVX' ) ) {
		// Installation and uninstallation hooks
		register_activation_hook(__FILE__, array('MSTW_CSVX', 'activate'));
		register_deactivation_hook(__FILE__, array('MSTW_CSVX', 'deactivate'));

		// instantiate the plugin class
		$mstw_csvx = new MSTW_CSVX( );
		
		// Add a link to the settings page onto the plugin page
		//mstw_log_msg( 'isset( $wp_plugin_template ): ' . isset( $wp_plugin_template ) );
		
		if( isset( $wp_plugin_template ) ) {
			//mstw_log_msg( '$wp_plugin_template: ' . $wp_plugin_template );
			// Add the settings link to the plugins page
			function plugin_settings_link( $links ) { 
				$settings_link = '<a href="options-general.php?page=wp_plugin_template">Settings</a>'; 
				array_unshift( $links, $settings_link ); 
				return $links; 
			} //End: function plugin_settings_link($links)
			
			
			$plugin = plugin_basename( __FILE__ ); 
			add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
			//mstw_log_msg( 'CSVX: set plugin_action_links_' . $plugin );
		}
	} //End: if( class_exists( 'MSTW_CSVX' ) )
//} //End: mstw_gl_init_export()
?>