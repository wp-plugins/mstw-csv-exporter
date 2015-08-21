<?php
/*---------------------------------------------------------------------
 * mstw-csv-exporter-fcns.php
 *	Defines the MSTW Exporter functions that grab the CPT data and
 *	build the CSV file. 
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
	
function mstw_csvx_export( ) {
	$mstw_csvx_export_check = isset( $_REQUEST['action'] ) ? 'export' : '';
	$mstw_csvx_export_type = isset( $_REQUEST['mstw_csvx_post_type'] ) ? $_REQUEST['mstw_csvx_post_type'] : '';
	
	if ( $mstw_csvx_export_check == 'export' && $mstw_csvx_export_type != '' ) {     
		echo mstw_csvx_generate( $mstw_csvx_export_type );
		exit;
	}
} //End: mstw_csvx_export( )

function mstw_csvx_generate( $input_post_type ) {
	// This is setting up a separate plugin for CSV Export
	
	$type_map = array( __( 'Game Locations - Locations', 'mstw-csv-exporter' )	=> 'game_locations',
	
					   __( 'Game Schedules - Games', 'mstw-csv-exporter' ) 	=> 'scheduled_games',
					   __( 'Game Schedules - Teams', 'mstw-csv-exporter' )		=> 'mstw_gs_teams',
					   __( 'Game Schedules - Schedules', 'mstw-csv-exporter' )	=> 'mstw_gs_schedules',
					   
					   __( 'Team Rosters (3.1.2 & before) - Players', 'mstw-csv-exporter' )	=> 'player', 
					   __( 'Team Rosters (3.1.2 & before) - Teams', 'mstw-csv-exporter' )	=> 'teams',
					   
					   __( 'Team Rosters (4.0 & later) - Players', 'mstw-csv-exporter' )	=> 'mstw_tr_player', 
					   __( 'Team Rosters (4.0 & later) - Teams', 'mstw-csv-exporter' )	=> 'mstw_tr_team',
					   
					   __( 'Schedules & Scoreboards - Games', 'mstw-csv-exporter' ) => 'mstw_ss_game',
					   __( 'Schedules & Scoreboards - Schedules', 'mstw-csv-exporter' ) => 'mstw_ss_schedule',
					   __( 'Schedules & Scoreboards - Sports', 'mstw-csv-exporter' ) => 'mstw_ss_sport',
					   __( 'Schedules & Scoreboards - Teams', 'mstw-csv-exporter' ) => 'mstw_ss_team',
					   __( 'Schedules & Scoreboards - Venues', 'mstw-csv-exporter' ) => 'mstw_ss_venue', 
					  );
	
	
	// Get the CPT or CT that is being exported
	//mstw_log_msg( '$input_post_type = ' . $input_post_type );
	$mstw_csvx_generate_post_type = $type_map[$input_post_type];
	
	//mstw_log_msg( 'input post_type = ' . $input_post_type );
	//mstw_log_msg( 'new post type = ' . $mstw_csvx_generate_post_type );

	// Get the custom fields (for CPT or CT) being exported
	//$mstw_csvx_generate_custom_fields = get_option('mstw_csvx_custom_fields');
	$fields_map = mstw_csvx_get_fields_map( );
		
	$mstw_csvx_generate_custom_fields = $fields_map[$mstw_csvx_generate_post_type];
	
	//mstw_log_msg( 'custom_fields = ' );
	//mstw_log_msg( $mstw_csvx_generate_custom_fields );
	
	//
	// Taxonomies ('teams' and 'mstw_tr_team') require special handling
	//
	if ( 'teams' == $mstw_csvx_generate_post_type or 
		 'mstw_tr_team' == $mstw_csvx_generate_post_type ) {
		// Get all terms for the taxonomy
		$mstw_csvx_generate_query = get_terms(  $mstw_csvx_generate_post_type );
		//mstw_log_msg( 'terms for ' . $mstw_csvx_generate_post_type . ' =' );
		//mstw_log_msg( $mstw_csvx_generate_query );
	}
	else {
		// Get all instances of the custom post type
		$mstw_csvx_generate_query = get_posts( array( 	'post_type' => $mstw_csvx_generate_post_type, 
														'post_status' => 'publish', 
														'posts_per_page' => -1
													 )
													);
	}	
	
	//
	// Check that we have posts (or terms) before processing them
	//
	$mstw_csvx_count_posts = count( $mstw_csvx_generate_query );
	//mstw_log_msg( 'post type= ' . $mstw_csvx_generate_post_type . ' nbr of posts= ' . $mstw_csvx_count_posts );

	if ( $mstw_csvx_count_posts <= 0 ) {
		mstw_log_msg( 'No posts found for type ' . $mstw_csvx_generate_post_type );
		//
		// could use a user msg too, but the setup is very weird
		//
	}
	else {
		// Build an array of the custom field values
		$mstw_csvx_generate_value_arr = array( );
		$i = 0; 
		
		foreach ( $mstw_csvx_generate_query as $post ) {
			//
			// Taxonomies ('teams' and 'mstw_tr_team') require special handling
			//
			if ( $mstw_csvx_generate_post_type != 'teams' and $mstw_csvx_generate_post_type != 'mstw_tr_team') {
				//$mstw_csvx_generate_post_values['post_title'] = array( get_the_title( $post->ID ) );
				//$mstw_csvx_generate_post_values['post_slug'] = array( get_post( $post->ID )->post_name );
			
				setup_postdata($post);	

				// get the custom field values for each instance of the custom post type 
				$mstw_csvx_generate_post_values = get_post_custom( $post->ID );
				$mstw_csvx_generate_post_values['post_title'] = array( get_the_title( $post->ID ) );
				$mstw_csvx_generate_post_values['post_slug'] = array( get_post( $post->ID )->post_name );
			}
			else {
				//Special handling for taxonomies
				$mstw_csvx_generate_post_values['team_name'] = array( $post->name ) ;
				$mstw_csvx_generate_post_values['team_slug'] = array( $post->slug );
				$mstw_csvx_generate_post_values['team_description'] = array( $post->description );
				$mstw_csvx_generate_post_values['team_ss_link'] = array( 'SS Link Test' );
				//mstw_log_msg( '$mstw_csvx_generate_post_values = ' );
				//mstw_log_msg( $mstw_csvx_generate_post_values );
			}
			if ( $mstw_csvx_generate_post_type == 'mstw_ss_venue' ) {
				$mstw_csvx_generate_post_values['venue_group'] = array( 'venue_group' );
			}
			else if ( $mstw_csvx_generate_post_type == 'mstw_ss_game' ) {
				$mstw_csvx_generate_post_values['game_scoreboard'] = array( 'game_scoreboard' );
			}
			else if ( $mstw_csvx_generate_post_type == 'player' ) {
				$mstw_csvx_generate_post_values['teams'] = array( 'teams' );
				$mstw_csvx_generate_post_values['player_photo'] = array( 'player_photo' );
				$mstw_csvx_generate_post_values['player_bio'] = array( 'player_bio' );
				
			}
			else if ( $mstw_csvx_generate_post_type == 'teams' ) {
				$mstw_csvx_generate_post_values['name'] = array( 'team_name' );
				$mstw_csvx_generate_post_values['slug'] = array( 'team_slug' );
				$mstw_csvx_generate_post_values['description'] = array( 'team_description' );
				
			}
			else if ( $mstw_csvx_generate_post_type == 'mstw_tr_team' ) {
				$mstw_csvx_generate_post_values['name'] = array( 'team_name' );
				$mstw_csvx_generate_post_values['slug'] = array( 'team_slug' );
				$mstw_csvx_generate_post_values['description'] = array( 'team_description' );
				$mstw_csvx_generate_post_values['team_ss_link'] = array( 'team_ss_link' );
				
			}
			else if ( $mstw_csvx_generate_post_type == 'mstw_tr_player' ) {
				$mstw_csvx_generate_post_values['player_teams'] = array( 'mstw_tr_team' );
				$mstw_csvx_generate_post_values['player_photo'] = array( 'player_photo' );
				$mstw_csvx_generate_post_values['player_bio'] = array( 'player_bio' );
				
			}
			
			//mstw_log_msg( '$mstw_csvx_generate_custom_fields[\'selectinput\']: ' );
			//mstw_log_msg( $mstw_csvx_generate_custom_fields['selectinput'] );
			foreach ( $mstw_csvx_generate_custom_fields['selectinput'] as $key=>$value ) {
				// check if each custom field value matches a custom field that is being exported
				if ( array_key_exists( $key, $mstw_csvx_generate_post_values ) ) {
					// if the the custom fields match, save them to the array of custom field values
					$mstw_csvx_generate_value_arr[$value][$i] = mstw_csvx_set_value( $mstw_csvx_generate_post_values, $key, $mstw_csvx_generate_post_type );
				}   
			}
			
			$i++; 
			
		} //End: foreach ( $mstw_csvx_generate_query as $post )
		
		// create a new array of values that reorganizes them in a new multidimensional array where each sub-array contains all of the values for one custom post instance
		$mstw_csvx_generate_value_arr_new = array();
		
		foreach( $mstw_csvx_generate_value_arr as $value ) {
		   $i = 0;
		   //mstw_log_msg( '$value: ' );
		   //mstw_log_msg( $value );
		   while ($i <= ($mstw_csvx_count_posts-1)) {
				//prevents php warnings for bad indices
				$value[$i] = ( isset( $value[$i] ) ) ? $value[$i] : '';
				//mstw_log_msg( '$i= ' . $i . ' ' . $value[$i] . '//' );
				$mstw_csvx_generate_value_arr_new[$i][] = $value[$i];
				$i++;
			}
		}
		
		//mstw_log_msg( 'in mstw_csvx_generate() ... $mstw_csvx_generate_value_arr: ' );
		//mstw_log_msg( $mstw_csvx_generate_value_arr );

		// build a filename based on the post type and the data/time
		$dtg = date('Ymd-His', current_time( 'timestamp', false ) );
		//die( $mstw_csvx_generate_post_type . '-' . $dtg . '-export.csv' );
		///$mstw_csvx_generate_csv_filename = $mstw_csvx_generate_post_type . '-' . $dtg . '-export.csv';
		$mstw_csvx_generate_csv_filename = $input_post_type . '-' . $dtg . '-export.csv';
		
		//output the headers for the CSV file
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$mstw_csvx_generate_csv_filename}");
		header("Expires: 0");
		header("Pragma: public");

		//open the file stream
		$fh = @fopen( 'php://output', 'w' );
		
		$headerDisplayed = false;
	 
		foreach ( $mstw_csvx_generate_value_arr_new as $data ) {
			// Add a header row if it hasn't been added yet -- using custom field keys from first array
			if ( !$headerDisplayed ) {	
				fputcsv( $fh, array_keys( $mstw_csvx_generate_value_arr ) );
				$headerDisplayed = true;
			}

			// Put the data from the new multi-dimensional array into the stream
			fputcsv($fh, $data);
		}
		
		// Close the file stream
		fclose($fh);	
	}
	
	// Make sure nothing else is sent, our file is done
	exit;
	
} //End: mstw_csvx_generate()

//--------------------------------------------------------------------
// MSTW_CSVX_SET_VALUE - handles the translation from raw DB ID keys to slugs
//
function mstw_csvx_set_value( $mstw_csvx_generate_post_values, $key, $post_type ) {
	//set the default return value
	
	$ret_val = $mstw_csvx_generate_post_values[$key]['0'];

	switch ( $post_type ) {
		case 'scheduled_games':
			if ( $key == 'gs_opponent_team' && $mstw_csvx_generate_post_values[$key]['0'] > 0 ) {
				$opponent_ID = $mstw_csvx_generate_post_values[$key]['0'];
				$args = array(  'post_type' => 'mstw_gs_teams',
								'post_status' => 'publish',
								'p' => $opponent_ID,
							  );
				//$teams_array = get_posts( $args );
				// should be one and only one element of array
				if( $teams_array = get_posts( $args ) ) {
					//$venue_slug = $venues_array[0]->post_name;
					$ret_val = $teams_array[0]->post_name;
				}
			}
			else if ( $key == '_mstw_gs_gl_location' && $mstw_csvx_generate_post_values[$key]['0'] > 0 ) {
				$venue_ID = $mstw_csvx_generate_post_values[$key]['0'];
				$args = array(  'post_type' => 'game_locations',
								'post_status' => 'publish',
								'p' => $venue_ID,
							  );
				// should be one and only one element of array
				if( $venues_array = get_posts( $args ) ) {
					//$venue_slug = $venues_array[0]->post_name;
					$ret_val = $venues_array[0]->post_name;
				}
			}
			break;
		case 'mstw_gs_teams':
			//schedule_team is the WP DB ID for the team CPT
			if ( $key == 'team_home_venue' && $mstw_csvx_generate_post_values[$key]['0'] > 0 ) {
				$venue_ID = $mstw_csvx_generate_post_values[$key]['0'];
				$args = array(  'post_type' => 'game_locations',
								'post_status' => 'publish',
								'p' => $venue_ID,
							  );
				//$venues_array = get_posts( $args );
				if ( $venues_array = get_posts( $args ) ) {
					$ret_val = $venues_array[0]->post_name;
				}
			
			}
			break;
		case 'mstw_gs_schedules':
			if ( $key == 'schedule_team' && $mstw_csvx_generate_post_values[$key]['0'] > 0 ) {
				//$team_ID = $mstw_csvx_generate_post_values[$key]['0'];
				$args = array(  'post_type' => 'mstw_gs_teams',
								'post_status' => 'publish',
								'p' => $mstw_csvx_generate_post_values[$key]['0'],
							  );
				if( $teams_array = get_posts( $args ) ) {
					$ret_val = $teams_array[0]->post_name;
				}
			}
			break;
		case 'mstw_ss_venue':
			if ( $key == 'venue_group' ) {
				$ret_val = '';
				if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
					$slug = $mstw_csvx_generate_post_values['post_slug'][0];
					//mstw_log_msg( 'in mstw_csvx_set_value $slug= ' . $slug );
					
					$venue_obj = get_page_by_path( $slug, OBJECT, 'mstw_ss_venue' );
					if( $venue_obj !== null ) {
						//mstw_log_msg( $venue_obj );
						$venue_id = $venue_obj->ID;
						//mstw_log_msg( 'taxonomy exists: ' . taxonomy_exists( 'mstw_ss_venue_group') . ' //' );
						//mstw_log_msg( 'in mstw_csvx_set_value $venue_id= ' . $venue_id );
						$venue_group = get_the_terms( $venue_id, 'mstw_ss_venue_group' );
						
						if ( $venue_group ) {
							foreach( $venue_group as $group ) {
								$ret_val .= $group->slug . ';';
							}
						}
					}
				}
			}
			break;
		case 'mstw_ss_game':
			if ( $key == 'game_scoreboard' ) {
				$ret_val = '';
				if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
					$slug = $mstw_csvx_generate_post_values['post_slug'][0];
					//mstw_log_msg( 'in mstw_csvx_set_value $slug= ' . $slug );
					
					$game_obj = get_page_by_path( $slug, OBJECT, 'mstw_ss_game' );
					if( $game_obj !== null ) {
						//mstw_log_msg( $venue_obj );
						$game_id = $game_obj->ID;
						//mstw_log_msg( 'in mstw_csvx_set_value $game_id= ' . $game_id );
						$scoreboards = get_the_terms( $game_id, 'mstw_ss_scoreboard' );
						
						if ( $scoreboards ) {
							foreach( $scoreboards as $scoreboard ) {
								$ret_val .= $scoreboard->slug . ';';
							}
						}
						//mstw_log_msg( '$ret_val= ' . $ret_val );
					} //End: if( $game_obj !== null ) {
				} //End: if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
			} //End: if ( $key == 'game_scoreboard' ) {
			break;
		case 'player':
		case 'mstw_tr_player':
			//mstw_log_msg( 'in mstw_csvx_set_value post_type/key= ' . $post_type .'/' .  $key );
			//if ( $key == 'player_bio' ) die( 'found it' );
			if ( 'teams' == $key or 'player_teams' == $key ) {
				$ret_val = '';
				if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
					$slug = $mstw_csvx_generate_post_values['post_slug'][0];
					//mstw_log_msg( 'in mstw_csvx_set_value $slug= ' . $slug );
					
					$player_obj = ( 'player' == $post_type ) ? get_page_by_path( $slug, OBJECT, 'player' ) :  get_page_by_path( $slug, OBJECT, 'mstw_tr_player' );
					
					//mstw_log_msg( '$player_obj =' );
					//mstw_log_msg( $player_obj );
					
					if( $player_obj !== null ) {
						//mstw_log_msg( $venue_obj );
						$player_id = $player_obj->ID;
						//mstw_log_msg( 'in mstw_csvx_set_value $player_id= ' . $player_id );
						$teams = ( 'player' == $post_type ) ? get_the_terms( $player_id, 'teams' ) : get_the_terms( $player_id, 'mstw_tr_team' );
						
						//mstw_log_msg( '$teams =' );
						//mstw_log_msg( $teams );
						
						if ( $teams ) {
							foreach( $teams as $team ) {
								$ret_val .= $team->slug . ';';
							}
						}
						//mstw_log_msg( '$ret_val= ' . $ret_val );
					} //End: if( $game_obj !== null ) {
				} //End: if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
			} //End: if ( $key == 'teams' ) {
			else if ( $key == 'player_photo' ) {
				$ret_val = '';
				//mstw_log_msg( 'in $key == \'player_photo\' ... ' );
				if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
					$slug = $mstw_csvx_generate_post_values['post_slug'][0];
					$player_obj = ( 'player' == $post_type ) ? get_page_by_path( $slug, OBJECT, 'player' ) :  get_page_by_path( $slug, OBJECT, 'mstw_tr_player' );
					if( $player_obj !== null ) {
						//mstw_log_msg( 'found a player object for player: ' . $slug );
						$thumbnail_id = get_post_thumbnail_id( $player_obj->ID );
						if( null !== $thumbnail_id ) {
								//mstw_log_msg( 'found a thumbnail: ' . $thumbnail_id );
								$ret_val = wp_get_attachment_url( $thumbnail_id );
						}
					}
				}
			}
			else if ( $key == 'player_bio' ) {
				//mstw_log_msg( 'in $key == \'player_bio\' ... ' );
				$ret_val = '';
				if ( isset( $mstw_csvx_generate_post_values['post_slug'] ) ) {
					$slug = $mstw_csvx_generate_post_values['post_slug'][0];
					$player_obj = get_page_by_path( $slug, OBJECT, 'player' );
					if( $player_obj !== null ) {
						$ret_val = $player_obj->post_content;
					}
				}
				//mstw_log_msg( '$ret_val = ' . $ret_val );
			}
			break;
		
		case 'teams':
		case 'mstw_tr_team':
			//mstw_log_msg( 'in case ' . $post_type . ' $key= ' . $key );
			//$mstw_csvx_generate_post_values, $key, $post_type
			//if ( array_key_exists( $key, $mstw_csvx_generate_post_values  ) ) {
				//mstw_log_msg( '$mstw_csvx_generate_post_values' );
				//mstw_log_msg( $mstw_csvx_generate_post_values );
			//}
			//else {
				//mstw_log_msg( 'key: ' . $key . ' does not exist' );
			//}
			switch( $key ) {
				case 'name': 
					$ret_val = $mstw_csvx_generate_post_values['team_name'][0];
					break;
				case 'slug':
					$ret_val = $mstw_csvx_generate_post_values['team_slug'][0];
					break;
				case 'description':
					$ret_val = $mstw_csvx_generate_post_values['team_description'][0];
					break;
				case 'team_ss_link':
					$ret_val = '';
					//mstw_log_msg( '$post_type/$key = ' . $post_type . '/' . $key );
					$team = $mstw_csvx_generate_post_values['team_slug'][0];
					//mstw_log_msg( '$team = ' . $team );
					
					if( $team && post_type_exists( 'mstw_ss_team' ) ) {
						//Check that $team is linked to a team in the MSTW S&S DB
						// $team is TR team slug; $team_obj is SS team obj
						if( $team_obj = mstw_tr_find_team_in_ss( $team ) ) {
							//mstw_log_msg( 'found $team_obj in SS ...' );
							//mstw_log_msg( 'slug= ' . $team_obj->post_name );
							
							$ret_val = $team_obj->post_name;
							
							
						} //End: if( $team_obj = mstw_tr_find_team_in_ss( $team ) )
							
						//else {
						//	mstw_log_msg( 'No team found in S&S for ' . $team );
						//}
				
		} //End: if( $team && post_type_exists( 'mstw_ss_team' ) )
					
					break;
				default:
					$ret_val = "Unknown key: {$key}";
					break;
			}
			break;
		case 'game_locations':
		default:
			//nothing to do
			break;
	}
	return $ret_val;
} //End: mstw_csvx_set_value( )

function mstw_csvx_get_fields_map( ) {
	return array(	'game_locations' => 
					array( 'selectinput' => 
						array(	'post_title'	=> 'venue_title',
								'post_slug'		=> 'venue_slug',
								'_mstw_gl_street' => 'venue_street',
								'_mstw_gl_city' => 'venue_city',
								'_mstw_gl_state' => 'venue_state',
								'_mstw_gl_zip' => 'venue_zip',
								'_mstw_gl_custom_url' => 'venue_map_url',
								'_mstw_gl_venue_url' => 'venue_url',
								),
						),
				'scheduled_games' =>
					array( 'selectinput' => 
						array(	'post_title'	=> 'game_title',
								'post_slug'		=> 'game_slug',
								//used??
								'_mstw_gs_sched_id'	=> 'game_sched_id',
								
								'_mstw_gs_game_time_tba' => 'game_time_tba',
								
								'_mstw_gs_unix_dtg'	=> 'game_unix_dtg',
								
								//this is the TEXT opponent
								'_mstw_gs_opponent' => 'game_opponent',
								//TEXT link
								'_mstw_gs_opponent_link' => 'game_opponent_link',
								
								//this is the teams DB opponent id/slug?
								'gs_opponent_team' => 'game_opponent_team',
								
								// location from game locations DB id/slug?
								'_mstw_gs_gl_location' => 'game_gl_location',
								
								//text location entry
								'_mstw_gs_location'	=> 'game_location',
								//text location link
								'_mstw_gs_location_link' => 'game_location_link',
								
								'_mstw_gs_home_game' => 'game_is_home_game',
								
								'_mstw_gs_game_result' => 'game_result',
								
								//media stuff
								'_mstw_gs_media_label_1' => 'game_media_label_1',
								'_mstw_gs_media_label_2' => 'game_media_label_2',
								'_mstw_gs_media_label_3' => 'game_media_label_3',
								'_mstw_gs_media_url_1' => 'game_media_url_1',
								'_mstw_gs_media_url_2' => 'game_media_url_2',
								'_mstw_gs_media_url_3' => 'game_media_url_3',
								
							
								),
						),
				'mstw_gs_teams' =>
					array( 'selectinput' => 
						array(	'post_title'	=> 'team_title',
								'post_slug'		=> 'team_slug',
							
								'team_full_name'	=> 'team_full_name',
								'team_short_name'	=> 'team_short_name',
								'team_full_mascot'	=> 'team_full_mascot',
								'team_short_mascot'	=> 'team_short_mascot',
								'team_home_venue'	=> 'team_home_venue',
								'team_link'			=> 'team_link',
								'team_logo'			=> 'team_logo',
								'team_alt_logo'		=> 'team_alt_logo',
								),
						),
				'mstw_gs_schedules' =>
					array( 'selectinput' => 
						array(	'post_title'	=> 'schedule_title',
								'post_slug'		=> 'schedule_slug',
						
								// slugs v. IDs ??
								// post_name (slug) is used for schedule ID
								//'schedule_id'	=> 'schedule_slug',
								'schedule_team'	=> 'schedule_team',
								),
						),
				// SCHEDULES & SCOREBOARDS		
				'mstw_ss_game' =>
					array( 'selectinput' => 
						array(	'post_title'		=> 'game_title',
								'post_slug'			=> 'game_slug',
								
								//BASIC GAME DATA
								'game_sched_id'		=> 'game_sched_id',
								
								'game_time_tba' 	=> 'game_time_tba',
								
								'game_unix_dtg'		=> 'game_unix_dtg',
								
								//this is the teams DB opponent id/slug
								'game_opponent_team' => 'game_opponent_team',
								
								// location from venues DB id/slug (for neutral sites)
								'game_gl_location' 	=> 'game_gl_location',
								
								'game_is_home_game' => 'game_is_home_game',
								
								//LEGACY FIELDS (DEPRECATED)
								//this is the TEXT opponent
								'game_opponent' 	=> 'game_opponent',
								//TEXT link
								'game_opponent_link' => 'game_opponent_link',
								
								//text location entry
								'game_location'		=> 'game_location',
								//text location link
								'game_location_link' => 'game_location_link',
								
								//GAME STATUS STUFF
								'game_our_score' 	=> 'game_our_score',
								'game_opp_score' 	=> 'game_opp_score',
								'game_curr_period' 	=> 'game_curr_period',
								'game_curr_time' 	=> 'game_curr_time',
								'game_is_final' 	=> 'game_is_final',
								'game_result' 		=> 'game_result',
								
								//media stuff
								'game_media_label_1' => 'game_media_label_1',
								'game_media_label_2' => 'game_media_label_2',
								'game_media_label_3' => 'game_media_label_3',
								'game_media_url_1' 	 => 'game_media_url_1',
								'game_media_url_2' 	 => 'game_media_url_2',
								'game_media_url_3' 	 => 'game_media_url_3',
								
								// scoreboard taxonomy
								'game_scoreboard'	 => 'game_scoreboard',
								),
						),
				'mstw_ss_team' =>
					array( 'selectinput' => 
						array(	'post_title'		=> 'team_title',
								'post_slug'			=> 'team_slug',
								
								'team_full_name'	=> 'team_full_name',
								'team_short_name'	=> 'team_short_name',
								'team_full_mascot'	=> 'team_full_mascot',
								'team_short_mascot'	=> 'team_short_mascot',
								'team_home_venue'	=> 'team_home_venue',
								'team_link'			=> 'team_link',
								'team_logo'			=> 'team_logo',
								'team_alt_logo'		=> 'team_alt_logo',
								),
						),
				'mstw_ss_schedule' =>
					array( 'selectinput' => 
						array(	'post_title'	=> 'schedule_title',
								'post_slug'		=> 'schedule_slug',
						
								// slugs v. IDs ??
								// post_name (slug) is used for schedule ID
								//'schedule_id'	=> 'schedule_slug',
								'schedule_team'	=> 'schedule_team',
								),
						),
				'mstw_ss_sport' =>
					array( 'selectinput' => 
						array(	'post_title'	=> 'sport_title',
								'post_slug'		=> 'sport_slug',
						
								'sport_season'	=> 'sport_season',
								'sport_gender'	=> 'sport_gender',
								),
						),
				'mstw_ss_venue' => 
					array( 'selectinput' => 
						array(	'post_title'	=> 'venue_title',
								'post_slug'		=> 'venue_slug',
								
								'venue_street' 	=> 'venue_street',
								'venue_city' 	=> 'venue_city',
								'venue_state' 	=> 'venue_state',
								'venue_zip' 	=> 'venue_zip',
								'venue_map_url' => 'venue_map_url',
								'venue_url' 	=> 'venue_url',
								'venue_group'	=> 'venue_group',
								),
						),
						
				// TEAM ROSTERS 3.1.2
				'player' => 
					array( 'selectinput' => 
						array(	'post_title'	=> 'player_title',
								'post_slug'		=> 'player_slug',
								
								'_mstw_tr_first_name'	=> 'player_first_name',
								'_mstw_tr_last_name' 	=> 'player_last_name',
								'_mstw_tr_number' 		=> 'player_number',
								'_mstw_tr_height' 		=> 'player_height',
								'_mstw_tr_weight' 		=> 'player_weight',
								'_mstw_tr_position' 	=> 'player_position',
								'_mstw_tr_year'			=> 'player_year',
								'_mstw_tr_experience'	=> 'player_experience',
								'_mstw_tr_age'			=> 'player_age',
								'_mstw_tr_home_town'	=> 'player_home_town',
								'_mstw_tr_last_school'	=> 'player_last_school',
								'_mstw_tr_country'		=> 'player_country',
								'_mstw_tr_bats'			=> 'player_bats',
								'_mstw_tr_throws'		=> 'player_throws',
								'_mstw_tr_other'		=> 'player_other',
								//teams taxonomy
								'teams'	 				=> 'player_teams',
								//featured image (thumbnail)
								'player_photo'			=> 'player_photo',
								//player bio is post content
								'player_bio'			=> 'player_bio',
								),
						),
					'teams' => 
						array( 'selectinput' => 
							array(	'name'			=> 'team_name',
									'slug'			=> 'team_slug',
									'description'	=> 'team_description',
									),
							),
							
					// TEAM ROSTERS 4.0
					'mstw_tr_team' => 
						array( 'selectinput' => 
							array(	'name'			=> 'team_name',
									'slug'			=> 'team_slug',
									'description'	=> 'team_description',
									'team_ss_link'	=> 'team_ss_link',
									),
							),
					'mstw_tr_player' => 
						array( 'selectinput' => 
							array(	'post_title'	=> 'player_title',
									'post_slug'		=> 'player_slug',
								
									'player_first_name'	=> 'player_first_name',
									'player_last_name' 	=> 'player_last_name',
									'player_number' 	=> 'player_number',
									'player_height' 	=> 'player_height',
									'player_weight' 	=> 'player_weight',
									'player_position' 	=> 'player_position',
									'player_year'		=> 'player_year',
									'player_experience'	=> 'player_experience',
									'player_age'		=> 'player_age',
									'player_home_town'	=> 'player_home_town',
									'player_last_school' => 'player_last_school',
									'player_country'	=> 'player_country',
									'player_bats'		=> 'player_bats',
									'player_throws'		=> 'player_throws',
									'player_other'		=> 'player_other',
									//teams taxonomy
									'player_teams'	 	=> 'player_teams',
									//featured image (thumbnail)
									'player_photo'		=> 'player_photo',
									//player bio is post content
									'player_bio'		=> 'player_bio',
									),
							),
					
				);

}
?>