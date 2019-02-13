<?php 

namespace Misc;

class Misc
{
    public function register(){
        echo "This is a register function from Misc.";
    }
}


// // Function to hide the select writer Meta box for authors
// add_filter( 'admin_body_class', 'user_role_admin_body_class' );

// function user_role_admin_body_class( $classes ) {
//     global $current_user;
//     foreach( $current_user->roles as $role )
//         $classes .= ' role-' . $role;
//     return trim( $classes );
// }


// /**
//  * Add a widget to the dashboard.
//  *
//  * This function is hooked into the 'wp_dashboard_setup' action below.
//  */
// function example_add_dashboard_widgets() {

// 	wp_add_dashboard_widget(
//                  'example_dashboard_widget',         // Widget slug.
//                  'Attendance',         // Title.
//                  'example_dashboard_widget_function' // Display function.
//         );	
// }
// add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

// /**
//  * Create the function to output the contents of our Dashboard Widget.
//  */
// function example_dashboard_widget_function() {

// 	echo "<button class='btn btn-sm btn-danger'>In Attendance</button><br><br>";
// 	echo "<button class='btn btn-sm btn-primary'>View Attendance</button>";
// }