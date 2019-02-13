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


require_once('inc\CPT\Subtitle.php');