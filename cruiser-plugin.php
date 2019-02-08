<?php 

/**
 * @package CruiserPlugin
 */

/*
Plugin Name: Cruiser Plugin
Plugin URI: https://greenivytechnology.com
Description: Plugin for several features in blog and Magazine for backend and frontend.
Version: 1.0.0
Author: Ayush Oli
Author URI: http://greenivytechnology.com
License: GPLv2 or Later
Text Domain: cruiser-plugin
License: GPL2 or Later
 */

/*
Cruiser Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Cruiser Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Cruiser Plugin. If not, see {URI to Plugin License}.
*/


// If this file is called directly then, abort.

defined( 'ABSPATH' ) or die( "Hey, don't try to be a hacker unless you are not." );

// Require once the Composer Autoload
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ){
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


// Require the Activate and Deactivate File with Composer
use Inc\Base\Activate;
use Inc\Base\Deactivate;


/**
 * Function of the activation and deactivation hook
 * Register activation and deactivation hook
 */ 
function activate_cruiser_plugin(){
	Activate::activate();
}

register_activation_hook( __FILE__, 'activate_cruiser_plugin' );



function deactivate_cruiser_plugin(){
	Deactivate::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_cruiser_plugin' );



// Require the register_services from Init.php for calling all the class.
if( class_exists( 'Inc\\Init' ) ){
	Inc\Init::register_services();
} 

// require_once( 'inc/CPT/CPT_metabox.php' );


require_once 'inc\CPT\CPT_sub.php';
require_once 'inc\CPT\CPT_metabox.php';



add_action('pre_get_posts', 'filter_posts_list');

function filter_posts_list($query)
{
    //$pagenow holds the name of the current page being viewed
     global $pagenow, $typenow;  

 	$user = wp_get_current_user();
    $allowed_roles = array('author');
    //Shouldn't happen for the admin, but for any role with the edit_posts capability and only on the posts list page, that is edit.php
    if(array_intersect($allowed_roles, $user->roles ) && ('edit.php' == $pagenow) &&  $typenow == 'topics')
    { 
    //global $query's set() method for setting the author as the current user's id
        $query->set(
	        'meta_query', array(
		        array(
		            'key'     => 'cruiser_writer_value_key',
		            'value'   => $user->display_name,
		            'compare' => '==',
		        ),
	        )
        ); // here you can set your custom meta field using meta_query.
    }
}