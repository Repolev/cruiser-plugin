<?php 

/**
 * @package  CruiserPlugin
 */

namespace Inc\Base;
use \Inc\Base\BaseController;

class Enqueue extends BaseController{


	public function register(){
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	function enqueue(){
		// Enqueue all my styles
		wp_enqueue_style( 'mypluginstyle', $this->plugin_url . 'assets/mystyle.css', __FILE__ );
		wp_enqueue_style( 'cruiser_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css', __FILE__ );
		wp_enqueue_script( 'mypluginscript', $this->plugin_url . 'assets/myscript.js', array('jquery'), __FILE__, true );
	}
}