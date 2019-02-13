<?php 

/**
 * @package  CruiserPlugin
 */
namespace Inc\Attendance;

class Attendance
{
	public function register(){
		// Add Widget in Dashboard
		add_action( 'wp_dashboard_setup', array( $this, 'example_add_dashboard_widgets') );
	}


	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	function example_add_dashboard_widgets() {

		wp_add_dashboard_widget(
	                 'example_dashboard_widget',         // Widget slug.
	                 'Attendance',         // Title.
	                 array( $this, 'example_dashboard_widget_function' ) // Display function.
	        );	
	}

	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	function example_dashboard_widget_function() {

		echo "<button class='btn btn-sm btn-danger'>In Attendance</button><br><br>";
		echo "<a href='" . get_dashboard_url() . "admin.php?page=cruiser_attendance' class='btn btn-sm btn-primary'>View Attendance</a>";
	}
}