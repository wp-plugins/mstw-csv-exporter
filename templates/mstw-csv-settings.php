<?php
/*------------------------------------------------------------------------------
 * MSTW-CSV-SETTINGS.PHP - displays the MSTW Exporter Settings screen, which is
 *		the only admin screen for MSTW CSV Exporter. (included by )
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
 *--------------------------------------------------------------------------*/
?>
<div class="wrap">
    <h2><?php _e( 'MSTW CSV Exporter Settings', 'mstw-csv-exporter' ) ?></h2>
    <!--<form method="post" action="options.php" id="csvx_choose_type">--> 
	<form action='options-general.php?page=mstw_csvx_template'>
        <?php 
		@settings_fields('mstw_csvx-group'); 
        @do_settings_fields('mstw_csvx-group'); 
        do_settings_sections('mstw_csvx_template'); 

		if ( post_type_exists( 'game_locations' ) or 
			 post_type_exists( 'scheduled_games' ) or 
			 post_type_exists( 'mstw_ss_game' ) or 
			 post_type_exists( 'player' ) ) { 
			
			submit_button( __( 'Export Selected Table', 'mstw-csv-exporter' ), 'primary', 'export', true, null );
			
			?>
			<p class='csvx-msg'>Depending on the size of your data tables, the export may take a while. There are no messages in this version of the plugin. Be patient and don't press any buttons until you have seen the CSV file download in your browser.</p>
		<?php
		} 
		?> 
    </form>
</div>