<?php
// Create DB Here
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name      = $wpdb->prefix . 'zip_codes';
if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

	$sql = "CREATE TABLE $table_name (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`zip_radius` tinytext NOT NULL,
		`zip_code` mediumtext NOT NULL,
		`zip_matches` longtext NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
} else {
	echo "THERE IS A DATABASE MATCH!";
}

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

