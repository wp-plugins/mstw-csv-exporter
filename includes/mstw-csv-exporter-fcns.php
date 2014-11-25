<?php
/*---------------------------------------------------------------------
 * mstw-csv-exporter-fcns.php
 *	Defines the MSTW Exporter functions that grab the CPT data and
 *	build the CSV file. 
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
	
	
	$type_map = array( __( 'Locations', 'mstw-csv-exporter' )	=> 'game_locations',
					   __( 'Games', 'mstw-csv-exporter' ) 	=> 'scheduled_games',
					   __( 'Teams', 'mstw-csv-exporter' )		=> 'mstw_gs_teams',
					   __( 'Schedules', 'mstw-csv-exporter' )	=> 'mstw_gs_schedules',
					  );
	
	
	// Get the custom post type that is being exported
	$mstw_csvx_generate_post_type = $type_map[$input_post_type];

	// Get the custom fields (for CPT) is being exported
	//$mstw_csvx_generate_custom_fields = get_option('mstw_csvx_custom_fields');
	$fields_map = 
		array(	'game_locations' => 
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
				);
	
	$mstw_csvx_generate_custom_fields = $fields_map[$mstw_csvx_generate_post_type];
	
	// Query the DB for all instances of the custom post type
	$mstw_csvx_generate_query = get_posts( array( 'post_type' => $mstw_csvx_generate_post_type, 
											  'post_status' => 'publish', 
											  'posts_per_page' => -1
											  )
										);
										
	// Count the number of instances of the custom post type
	$mstw_csvx_count_posts = count( $mstw_csvx_generate_query );

	if ( $mstw_csvx_count_posts == 0 ) {
		mstw_log_msg( 'No posts found for type ' . $mstw_csvx_generate_post_type );
		//need a user msg too
	}
	
	// Build an array of the custom field values
	$mstw_csvx_generate_value_arr = array();
	$i = 0; 
	foreach ( $mstw_csvx_generate_query as $post ): setup_postdata($post);	

		// get the custom field values for each instance of the custom post type 
		$mstw_csvx_generate_post_values = get_post_custom( $post->ID );
		$mstw_csvx_generate_post_values['post_title'] = array( get_the_title( $post->ID ) );
		$mstw_csvx_generate_post_values['post_slug'] = array( get_post( $post->ID )->post_name );
		
		foreach ( $mstw_csvx_generate_custom_fields['selectinput'] as $key=>$value ) {
			// check if each custom field value matches a custom field that is being exported
			if ( array_key_exists( $key, $mstw_csvx_generate_post_values ) ) {
				// if the the custom fields match, save them to the array of custom field values
				$mstw_csvx_generate_value_arr[$value][$i] = mstw_csvx_set_value( $mstw_csvx_generate_post_values, $key, $mstw_csvx_generate_post_type );
			}   
		}
		
		$i++; 
		
	endforeach;	
	
	// create a new array of values that reorganizes them in a new multidimensional array where each sub-array contains all of the values for one custom post instance
	$mstw_csvx_generate_value_arr_new = array();
	
	foreach( $mstw_csvx_generate_value_arr as $value ) {
		   $i = 0;
		   while ($i <= ($mstw_csvx_count_posts-1)) {
			 $mstw_csvx_generate_value_arr_new[$i][] = $value[$i];
			$i++;
		}
	}

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
		case 'game_locations':
		default:
			//nothing to do
			break;
	}
	return $ret_val;
} //End: mstw_csvx_set_value( )
?>