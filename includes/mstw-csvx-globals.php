<?php
/*------------------------------------------------------------------------
 * mstw-csvx-globals.php - define some convenience globals to make life easier
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
 *----------------------------------------------------------------------*/
	 
	if ( !defined( 'MSTW_CSVX_THEME_DIR' ) )
		define( 'MSTW_CSVX_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template() );

	if ( !defined( 'MSTW_CSVX_PLUGIN_NAME' ) )
		define( 'MSTW_CSVX_PLUGIN_NAME', trim( dirname( plugin_basename( __DIR__ ) ), '/' ) );

	if ( !defined( 'MSTW_CSVX_PLUGIN_DIR' ) )
		define( 'MSTW_CSVX_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MSTW_CSVX_PLUGIN_NAME );
		
	if ( !defined( 'MSTW_CSVX_PLUGIN_FILE' ) )
		define( 'MSTW_CSVX_PLUGIN_FILE', MSTW_CSVX_PLUGIN_DIR . "/" . MSTW_CSVX_PLUGIN_NAME . ".php" );

	if ( !defined( 'MSTW_CSVX_PLUGIN_URL' ) )
		define( 'MSTW_CSVX_PLUGIN_URL', WP_PLUGIN_URL . '/' . MSTW_CSVX_PLUGIN_NAME );
		
	if ( !defined( 'MSTW_CSVX_INCLUDES_URL' ) )
		define( 'MSTW_CSVX_INCLUDES_URL', MSTW_CSVX_PLUGIN_URL . '/includes' );
		
	if ( !defined( 'MSTW_CSVX_INCLUDES_DIR' ) )
		define( 'MSTW_CSVX_INCLUDES_DIR', MSTW_CSVX_PLUGIN_DIR . '/includes' );
		
	if ( !defined( 'MSTW_CSVX_IMAGES_URL' ) )
		define( 'MSTW_CSVX_IMAGES_URL', MSTW_CSVX_PLUGIN_URL . '/images' );
		
	if ( !defined( 'MSTW_CSVX_IMAGES_DIR' ) )
		define( 'MSTW_CSVX_IMAGES_DIR', MSTW_CSVX_PLUGIN_DIR . '/images' );
		
	if ( !defined( 'MSTW_CSVX_JS_URL' ) )
		define( 'MSTW_CSVX_JS_URL', MSTW_CSVX_PLUGIN_URL . '/js' );
		
	if ( !defined( 'MSTW_CSVX_JS_DIR' ) )
		define( 'MSTW_CSVX_JS_DIR', MSTW_CSVX_PLUGIN_DIR . '/js' );
		
	if ( !defined( 'MSTW_CSVX_CSS_URL' ) )
		define( 'MSTW_CSVX_CSS_URL', MSTW_CSVX_PLUGIN_URL . '/css' );
		
	if ( !defined( 'MSTW_CSVX_CSS_DIR' ) )
		define( 'MSTW_CSVX_CSS_DIR', MSTW_CSVX_PLUGIN_DIR . '/css' );
		
	//---------------------------------------------------------------------
	//CREATE A PLUGIN VERSION OPTION ... COULD BE USEFUL FOR UPGRADES
	//
	if ( !defined( 'MSTW_CSVX_VERSION_KEY' ) )
		define( 'MSTW_CSVX_VERSION_KEY', 'mstw_csvx_version' );

	if ( !defined( 'MSTW_CSVX_VERSION_NBR' ) )
		define( 'MSTW_CSVX_VERSION_NBR', '0.a' );

	add_option( MSTW_CSVX_VERSION_KEY, MSTW_CSVX_VERSION_NBR );
	
	
	/*echo "<p>MSTW_CSVX_THEME_DIR: " . MSTW_CSVX_THEME_DIR . "</p>";
	echo "<p>MSTW_CSVX_PLUGIN_NAME: " . MSTW_CSVX_PLUGIN_NAME . "</p>";
	echo "<p>MSTW_CSVX_PLUGIN_DIR: " . MSTW_CSVX_PLUGIN_DIR . "</p>";
	echo "<p>MSTW_CSVX_PLUGIN_FILE: " . MSTW_CSVX_PLUGIN_FILE . "</p>";
	echo "<p>MSTW_CSVX_PLUGIN_URL: " . MSTW_CSVX_PLUGIN_URL . "</p>";
	echo "<p>MSTW_CSVX_INCLUDES_URL: " . MSTW_CSVX_INCLUDES_URL . "</p>";
	echo "<p>MSTW_CSVX_INCLUDES_DIR: " . MSTW_CSVX_INCLUDES_DIR . "</p>";
	echo "<p>MSTW_CSVX_JS_URL: " . MSTW_CSVX_JS_URL . "</p>";
	echo "<p>MSTW_CSVX_JS_DIR: " . MSTW_CSVX_JS_DIR . "</p>";
	echo "<p>MSTW_CSVX_IMAGES_URL: " . MSTW_CSVX_IMAGES_URL . "</p>";
	echo "<p>MSTW_CSVX_IMAGES_DIR: " . MSTW_CSVX_IMAGES_DIR . "</p>";
	echo "<p>MSTW_CSVX_CSS_URL: " . MSTW_CSVX_CSS_URL . "</p>";
	echo "<p>MSTW_CSVX_CSS_DIR: " . MSTW_CSVX_CSS_DIR . "</p>";

	echo "<p>VERSION: " . get_option( MSTW_CSVX_VERSION_KEY ) . "</p>";
	
	echo "<p>PLUGIN_DIR: " . WP_PLUGIN_DIR . "</p>";
	*/
	
?>