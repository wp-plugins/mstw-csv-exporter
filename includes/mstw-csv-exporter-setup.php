<?php
/*---------------------------------------------------------------------
 * mstw-csv-exporter-setup.php
 *	Defines the MSTW_CSVX_Settings class plus a couple of 
 *	helper functions. 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *--------------------------------------------------------------------*/ 
 
if(!class_exists('MSTW_CSVX_Settings')) {
	class MSTW_CSVX_Settings {
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	register_setting( 'mstw_csvx-group', 'mstw_csvx_post_type' );
        	register_setting( 'mstw_csvx-group', 'mstw_csvx_custom_fields' );

        	add_settings_section(
        	    'mstw_csvx_template-section', 
        	    //__( 'CSV Export Settings', 'mstw-csv-exporter' ),
				'',
        	    array(&$this, 'settings_section_mstw_csvx_template'), 
        	    'mstw_csvx_template'
        	);
        	
			if ( post_type_exists( 'game_locations' ) or post_type_exists( 'scheduled_games' ) or post_type_exists( 'mstw_ss_game' ) ) {
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Data Table to Export', 'mstw-csv-exporter' ), 
					array(&$this, 'settings_field_select_post_type'), 
					'mstw_csvx_template', 
					'mstw_csvx_template-section'  
				);
			}
           
        } // END public static function activate
        
        public function settings_section_mstw_csvx_template( ) {
            _e( 'Select the data table to export, save it, then click Export. Export file will be named "Table-Date_Time-export.csv". For example, "Games-20140214_043307-export.csv".', 'mstw-csv-exporter' );
			if( !post_type_exists( 'game_locations' ) ) {
				echo '<p class="csvx-msg">' . __( 'Install and activate the MSTW Game Locations plugin before exporting locations (venues).', 'mstw-csv-exporter' ) . '</p>';
			}
			if( !post_type_exists( 'scheduled_games' ) ) {
				echo '<p class="csvx-msg">' . __( 'Install and activate the MSTW Game Schedules plugin before exporting schedules, teams, and games.', 'mstw-csv-exporter' ) . '</p>';
			}
			if( !post_type_exists( 'mstw_ss_game' ) ) {
				echo '<p class="csvx-msg">' . __( 'Install and activate the MSTW Schedules & Scoreboards plugin before exporting schedules, teams, games, sports, and venues.', 'mstw-csv-exporter' ) . '</p>';
			}
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_select_post_type( ) {
		
			//This is a patch
			$options = '';
			
			$i = 0;
			$items = array();
			//this is the original game locations, now deprecated
			if( post_type_exists( 'game_locations' ) ) {
				$options = __( 'Game Locations - Locations', 'mstw-csv-exporter' );
				$items[$i] = __( 'Game Locations - Locations', 'mstw-csv-exporter' );
				$i++;
			}
			
			//this is the original game schedules, now deprecated
			if ( post_type_exists( 'scheduled_games' ) ) {
				if ( $options == '' ) {
					$options = __( 'Game Schedules - Games', 'mstw-csv-exporter' );
				}
				// if we have games, then we have all three
				$items[$i] = __( 'Game Schedules - Games', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Game Schedules - Schedules', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Game Schedules - Teams', 'mstw-csv-exporter' );
				$i++;
			}
			
			if ( post_type_exists( 'mstw_ss_game' ) ) {
				if ( $options == '' ) {
					$options = __( 'Schedules & Scoreboards - Games', 'mstw-csv-exporter' );
				}
				// if we have games, then we have all
				$items[$i] = __( 'Schedules & Scoreboards - Games', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Schedules & Scoreboards - Schedules', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Schedules & Scoreboards - Sports', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Schedules & Scoreboards - Teams', 'mstw-csv-exporter' );
				$i++;
				$items[$i] = __( 'Schedules & Scoreboards - Venues', 'mstw-csv-exporter' );
			}
			
            // echo a proper input type="text"
			if ( $items ) {
				foreach ( $items as $item ) {
					$checked = ( $options == $item ) ? ' checked="checked" ' : '';
					echo '<input type="radio" id="csvx_post_type_' . $item . '" name="mstw_csvx_post_type" value="' . $item . '" ' . $checked . '" />';
					echo '<label for=csvx_post_type_' . $item . '> '. $item . '</label>';
					echo ' <br />';
				}
			}
        } //End: public function settings_field_select_post_type( )
        
		
        /**
         * add a menu
         */		
		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_menu_page(
        	    __( 'CSV Export', 'mstw-csv-exporter' ),
        	    __( 'CSV Export', 'mstw-csv-exporter' ),
        	    'manage_options', 
        	    'mstw_csvx_template', 
        	    array(&$this, 'plugin_settings_page'),
				plugin_dir_url( __FILE__ ) . 'mstw-admin-menu-icon.png', //$menu_icon,
				"58.95" //priority ... just above the Appearance menu
        	);
        } // END public function add_menu()
    
        //
		// 	Menu Callback
        //		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
			$path = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __DIR__ ) );
        	require_once( sprintf("%s/templates/mstw-csv-settings.php", $path ));
			
        } // END public function plugin_settings_page()
    } // END class mstw_csvx_template_Settings
} // END if(!class_exists('mstw_csvx_template_Settings'))

function mstw_csvx_mstw_csvx_generate_post_meta_keys( $post_type ) {
	global $wpdb;
    $query = "
        SELECT DISTINCT($wpdb->postmeta.meta_key) 
        FROM $wpdb->posts 
        LEFT JOIN $wpdb->postmeta 
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
        WHERE $wpdb->posts.post_type = '%s' 
        AND $wpdb->postmeta.meta_key != '' 
        AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
        AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
    ";
    $meta_keys = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );
    set_transient( $post_type.'post_meta_keys', $meta_keys, 60*60*24 ); # 1 Day Expiration
    return $meta_keys;
}

function mstw_csvx_get_post_meta_keys( $post_type ) {
    $cache = get_transient( $post_type.'post_meta_keys' );
    $meta_keys = $cache ? $cache : mstw_csvx_generate_post_meta_keys( $post_type );
    return $meta_keys;
}

function mstw_csvx_checkboxes_fix( $input ) {
   $options = get_option( 'mstw_csvx_custom_fields' );
   $merged = $options;
   $merged[] = $input;
}