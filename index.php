<?php
/*
Plugin Name: Zip Code Service Area Plugin
Plugin URI:  https://github.com/Mizner/
Description:
Version:     0.0.1
Author:     Michael Mizner <michaelmizner@gmail.com>
Author URI:  https://github.com/Mizner
License:     MIT
License URI: https://opensource.org/licenses/MIT
*/


defined( "ABSPATH" ) or die( "No script kiddies please!" );

register_activation_hook( __FILE__, 'service_areas_create_db' );
function service_areas_create_db() {
	require_once "inc/create_table.php";
}

add_action( "admin_enqueue_scripts", "service_areas_enqueue_scripts" );
function service_areas_enqueue_scripts() {
	// Load Scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( "service_areas_css", plugins_url( "./dist/zcsa.min.css", __FILE__ ) );
	wp_enqueue_script( "service_areas_js", plugins_url( "./dist/zcsa.min.js", __FILE__ ) );
	wp_localize_script( "service_areas_js", "SERVICE_AREA", [
		"security" => wp_create_nonce( "service_area_ajax_nonce" ),
		"success"  => "success!",
		"error"    => "error!"
	] );
}

// Ajax

require_once( "inc/ajax_post.php" );

// Admin Menu & Page
require_once( "inc/dashboard.php" );
