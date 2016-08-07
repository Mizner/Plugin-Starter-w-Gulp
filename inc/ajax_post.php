<?php
function save_zips() {

	if ( ! check_admin_referer( "service_area_ajax_nonce", "security" ) ) {
		return wp_send_json_error( "Invalid Nonce" );
	}
	if ( ! current_user_can( "manage_options" ) ) {
		return wp_send_json_error( "Sorry, you are not allowed to do this." );
	}

	$post_zip_code    = $_POST["zip_code"];
	$post_zip_matches = $_POST["zip_matches"];
	$post_zip_radious = $_POST["zip_radius"];

	global $wpdb;
	$wpdb->insert(
		"wp_zip_codes",
		[
			"zip_code"    => $post_zip_code,
			"zip_matches" => $post_zip_matches,
			"zip_radius"  => $post_zip_radious
		],
		[ "%s" ] );

	$zip_codes = zip_codes();
	$the_fields = "";
	foreach ( $zip_codes as $item => $value ) {
		$the_fields .= $value->zip_matches . ",";
	};

	$directory   = "../wp-content/uploads/";
	$file        = 'serviceareas.csv';
	$fullpath    = $directory . $file;
	$csv_handler = fopen( $fullpath, 'w' );
	fwrite( $csv_handler, $the_fields );
	fclose( $csv_handler );

	exit(); //prevent 0 in the return




}

add_action( 'wp_ajax_save_zips', 'save_zips' ); //admin side
add_action( 'wp_ajax_nopriv_save_zips', 'save_zips' ); //for frontend


function remove_zips() {

	if ( ! check_admin_referer( "service_area_ajax_nonce", "security" ) ) {
		return wp_send_json_error( "Invalid Nonce" );
	}
	if ( ! current_user_can( "manage_options" ) ) {
		return wp_send_json_error( "Sorry, you are not allowed to do this." );
	}

	$post_zip_code    = $_POST["zip_code"];

	global $wpdb;
	$wpdb->delete(
		"wp_zip_codes",
		[
			"zip_code"    => $post_zip_code,
		],
		[ "%s" ] );

	$zip_codes = zip_codes();
	$the_fields = "";
	foreach ( $zip_codes as $item => $value ) {
		$the_fields .= $value->zip_matches . ",";
	};

	$directory   = "../wp-content/uploads/";
	$file        = 'serviceareas.csv';
	$fullpath    = $directory . $file;
	$csv_handler = fopen( $fullpath, 'w' );
	fwrite( $csv_handler, $the_fields );
	fclose( $csv_handler );

	exit(); //prevent 0 in the return




}

add_action( 'wp_ajax_remove_zips', 'remove_zips' ); //admin side
add_action( 'wp_ajax_nopriv_remove_zips', 'remove_zips' ); //for frontend

