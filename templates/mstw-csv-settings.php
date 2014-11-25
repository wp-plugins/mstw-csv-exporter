<div class="wrap">
    <h2><?php _e( 'MSTW CSV Exporter Settings', 'mstw-csv-exporter' ) ?></h2>
    <!--<form method="post" action="options.php" id="csvx_choose_type">--> 
	<form action='options-general.php?page=mstw_csvx_template'>
        <?php 
		@settings_fields('mstw_csvx-group'); 
        @do_settings_fields('mstw_csvx-group'); 
        do_settings_sections('mstw_csvx_template'); 

		if ( post_type_exists( 'game_locations' ) or post_type_exists( 'scheduled_games' ) ) { 
			/*<!--
			<input type="hidden" name="action" value="export" />
			<input type="hidden" name="csvx_type" id="csvx_type" value="" />
			-->*/
			submit_button( __( 'Export Selected Table', 'mstw-csv-exporter' ), 'primary', 'export', true, null );
		} 
		?> 
    </form>
</div>