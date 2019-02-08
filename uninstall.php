<?php 
/**
 * Trigger this file on Plugin uninstall
 * 
 * @package CruiserPlugin
 */

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
	die;
}

// $topics = get_posts( array( 'post_type' => 'topics', 'numberposts' => -1 ) );

// foreach( $topics as $topic ){
// 	wp_delete_post( $book->ID,  );
// }

// Access the database via SQL
global $wpdb;

$wpdb->query( "DELETE FROM " . $wpdb->prefix ."_posts WHERE post_type ='topics' " );
$wpdb->query( "DELETE FROM " . $wpdb->prefix ."_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );