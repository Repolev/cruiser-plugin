<?php 

namespace Inc;


class Misc{

	public function register(){
		
		// Function to hide the select writer Meta box for authors
		add_filter( 'admin_body_class', array( $this, 'user_role_admin_body_class' ) );
	}


	function user_role_admin_body_class( $classes ) {
	    global $current_user;
	    foreach( $current_user->roles as $role )
	        $classes .= ' role-' . $role;
	    return trim( $classes );
	}
	
}