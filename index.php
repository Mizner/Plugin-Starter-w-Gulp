<?php
/*
Plugin Name: Plugin Starter w/ Gulp
Plugin URI:  https://github.com/Mizner/woocommerce-orders-and-scheduling.git
Description:
Version:     0.0.1
Author:     Michael Mizner <michaelmizner@gmail.com>
Author URI:  https://github.com/Mizner
License:     MIT
License URI: https://opensource.org/licenses/MIT
*/

$project = "projectname"; // This name should match the project name in gulpfile.js

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'wp_enqueue_scripts', 'the_scheduler_enqueue_scripts' );
function the_plugin_enqueue_scripts() {
	// Load Scripts
	wp_enqueue_style( 'the_plugin_CSS', plugins_url( './dist/' . $project . '.min.css', __FILE__ ) );
	wp_enqueue_script( 'the_plugin_JS', plugins_url( './dist/.' . $project . '.min.js', __FILE__ ) );
}


/*
 * Query Example
 */

add_shortcode( 'the_plugin_shortcode', 'the_plugin_shortcode' );
function the_plugin_shortcode() {
	require_once( 'inc/example-query.php' );
}
