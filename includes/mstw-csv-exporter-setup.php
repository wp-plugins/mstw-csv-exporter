<?php
/*---------------------------------------------------------------------
 * mstw-csv-exporter-setup.php
 *	Defines the MSTW_CSVX_Settings class plus a couple of 
 *	helper functions. 
 *
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
        	
			if ( post_type_exists( 'game_locations' ) ) {
				add_settings_section(
					'mstw_csvx_game_locations-section', 
					'', //Don't want a heading or intro text
					array(&$this, 'blank_section_text'), 
					'mstw_csvx_template'
				);
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Game Locations (Deprecated):', 'mstw-csv-exporter' ), 
					array(&$this, 'game_locations_settings_fields'), 
					'mstw_csvx_template', 
					'mstw_csvx_game_locations-section'  
				);		
			} //End: post type game_locations
			
			if ( post_type_exists( 'scheduled_games' ) ) {
				add_settings_section(
					'mstw_csvx_scheduled_games-section', 
					'', //Don't want a heading or intro text
					array(&$this, 'blank_section_text'), 
					'mstw_csvx_template'
				);
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Game Schedules (Deprecated):', 'mstw-csv-exporter' ), 
					array(&$this, 'game_schedules_settings_fields'), 
					'mstw_csvx_template', 
					'mstw_csvx_scheduled_games-section'  
				);
			} //End: post type scheduled_games
			
			if ( post_type_exists( 'mstw_ss_game' ) ) {
				add_settings_section(
					'mstw_csvx_schedules_scoreboards-section', 
					'',  //Don't want a heading or intro text
					array(&$this, 'blank_section_text'), 
					'mstw_csvx_template'
				);
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Schedules & Scoreboards:', 'mstw-csv-exporter' ), 
					array(&$this, 'schedules_scoreboards_settings_fields'), 
					'mstw_csvx_template', 
					'mstw_csvx_schedules_scoreboards-section'  
				);	
				
			} //End: post type mstw_ss_game
			
			//
			// Team Rosters version 3.1.2
			//
			if( post_type_exists( 'player' ) ) {
				add_settings_section(
					'mstw_csvx_team_rosters-section', 
					'', //__( 'CSV Team Rosters (3.1.2 & before)', 'mstw-csv-exporter' ),
					array(&$this, 'blank_section_text'), 
					'mstw_csvx_template'
				);
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Team Rosters (3.1.2 & before):', 'mstw-csv-exporter' ), 
					array( &$this, 'team_rosters_settings_fields' ), 
					'mstw_csvx_template', 
					'mstw_csvx_team_rosters-section'
				);
			} //End: post type player
			
			//
			// Team Rosters version 4.0+
			//
			if( post_type_exists( 'mstw_tr_player' ) ) {
				add_settings_section(
					'mstw_csvx_team_rosters-4-section', 
					'', //__( 'CSV Team Rosters (4.0 & later)', 'mstw-csv-exporter' ),
					array( &$this, 'blank_section_text' ), 
					'mstw_csvx_template'
				);
				add_settings_field(
					'mstw_csvx_post_type', 
					__( 'Team Rosters (4.0 & later):', 'mstw-csv-exporter' ), 
					array( &$this, 'team_rosters_4_settings_fields' ), 
					'mstw_csvx_template', 
					'mstw_csvx_team_rosters-4-section'
				);
			} //End: post type mstw_tr_player
           
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
			if( !post_type_exists( 'player' ) ) {
				echo '<p class="csvx-msg">' . __( 'Install and activate the MSTW Team Rosters plugin (pre-version 4.0) before exporting players & teams.', 'mstw-csv-exporter' ) . '</p>';
			}
			if( !post_type_exists( 'mstw_tr_player' ) ) {
				echo '<p class="csvx-msg">' . __( 'Install and activate the MSTW Team Rosters plugin (4.0 or later) before exporting players & teams.', 'mstw-csv-exporter' ) . '</p>';
			}
        }
		
		//
		// Dumb function to remove settings section text (in case we want to add something later
		//
		public function blank_section_text( $section ) {
			// nada
			//echo 'This is the team rosters pre 4.0 section.';
		}
		
		//
		// Game Locations plugin
		// 
		public function game_locations_settings_fields( ) {
			
			$this->create_settings_fields( 'game_locations' );
			
		}
		
		//
		// Game Schedules plugin
		// 
		public function game_schedules_settings_fields( ) {

			$this->create_settings_fields( 'game_schedules' );
			
		}
		
		//
		// Schedules & Scoreboards plugin
		// 
		public function schedules_scoreboards_settings_fields( ) {

			$this->create_settings_fields( 'schedules_scoreboards' );
			
		}

		//
		// Team Rosters 3.1.2 settings fields
		//
        public function team_rosters_settings_fields( ) {
			//mstw_log_msg( $section['id'] );
	
			$this->create_settings_fields( 'team_rosters' );
		}
		
		//
		// Team Rosters 4.0+ settings fields
		//
        public function team_rosters_4_settings_fields( ) {
			//mstw_log_msg( $section['id'] );
	
			$this->create_settings_fields( 'team_rosters_4' );
		}
		
		//
        // This function creates controls for all settings sections
        //
		public function create_settings_fields( $section='team_rosters' ) {
		
				//This is a patch
				$options = '';
				
				$i = 0;
				$items = array();
				
				switch( $section ) {
					case 'schedules_scoreboards':
						if ( post_type_exists( 'mstw_ss_game' ) ) {
							//$options = ( $options == '' ) ? __( 'Schedules & Scoreboards', 'mstw-csv-exporter' ) : $options;
							$options = __( 'Schedules & Scoreboards', 'mstw-csv-exporter' );
							$items[$i] = __( 'Schedules & Scoreboards - Games', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Schedules & Scoreboards - Schedules', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Schedules & Scoreboards - Sports', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Schedules & Scoreboards - Teams', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Schedules & Scoreboards - Venues', 'mstw-csv-exporter' );
							$i++;
						}
						break;
						
					case 'team_rosters':
						if ( post_type_exists( 'player' ) ) {
							//$options = ( $options == '' ) ? __( 'Team Rosters (3.1.2 & before)', 'mstw-csv-exporter' ) : $options;
							$options = __( 'Team Rosters (3.1.2 & before)', 'mstw-csv-exporter' );
							$items[$i] = __( 'Team Rosters (3.1.2 & before) - Players', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Team Rosters (3.1.2 & before) - Teams', 'mstw-csv-exporter' );
							$i++;
						}
						break;
						
					case 'team_rosters_4':
						if ( post_type_exists( 'mstw_tr_player' ) ) {
							//$options = ( $options == '' ) ? __( 'Team Rosters (4.0 & later)', 'mstw-csv-exporter' ) : $options;
							$options = __( 'Team Rosters (4.0 & later)', 'mstw-csv-exporter' );
							$items[$i] = __( 'Team Rosters (4.0 & later) - Players', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Team Rosters (4.0 & later) - Teams', 'mstw-csv-exporter' );
							$i++;
						}
						break;
					
					case 'game_locations':
						if( post_type_exists( 'game_locations' ) ) {
							$options = __( 'Game Locations - Locations', 'mstw-csv-exporter' );
							$items[$i] = __( 'Game Locations - Locations', 'mstw-csv-exporter' );
							$i++;
						}
						break;
					
					case 'game_schedules':
						if ( post_type_exists( 'scheduled_games' ) ) {
							//$options = ( $options == '' ) ? __( 'Game Schedules - Games', 'mstw-csv-exporter' ) : $options;
							$options = __( 'Game Schedules - Games', 'mstw-csv-exporter' );
							$items[$i] = __( 'Game Schedules - Games', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Game Schedules - Schedules', 'mstw-csv-exporter' );
							$i++;
							$items[$i] = __( 'Game Schedules - Teams', 'mstw-csv-exporter' );
							$i++;
						}
						break;
					default:
						break;
					
				} //End: switch( $section )
				
				// echo the html input fields
				if ( $items ) {
					foreach ( $items as $item ) {
						$checked = ( $options == $item ) ? ' checked="checked" ' : '';
						echo '<input type="radio" id="csvx_post_type_' . $item . '" name="mstw_csvx_post_type" value="' . $item . '" ' . $checked . '" />';
						echo '<label for=csvx_post_type_' . $item . '> '. $item . '</label>';
						echo ' <br />';
					}
				}
			
		} //End: create_settings_fields()
        
        //
        // Add the CSV Exporter menu
        //		
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

