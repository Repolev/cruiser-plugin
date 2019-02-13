<?php 

/**
 * @package  CruiserPlugin
 */

namespace Inc\CPT;

class CPT{

	public function register(){
		add_action( 'init', array( $this, 'custom_post_type' ) );

		add_action( 'manage_topics_posts_columns', array( $this, 'topics_custom_columns' ) );

		add_action( 'manage_topics_posts_custom_column', array( $this, 'topics_custom_column_manage' ), 10, 2 );

		add_action( 'wp_ajax_nopriv_update_post_topic_writer', array( $this,'update_post_topic_writer') );
    	add_action( 'wp_ajax_update_post_topic_writer', array( $this, 'update_post_topic_writer') );

		add_action('pre_get_posts', array( $this, 'topics_custom_filter' ));

	}

	function custom_post_type(){

		$args = [
			'label' => 'Topics',
			'public' => false,
	        'show_ui' => true,
	        'capability_type' => 'post',
	        'hierarchical' => false,
	        'rewrite' => array('slug' => 'topics'),
	        'query_var' => true,
	        'menu_icon' => 'dashicons-carrot',
	        'supports' => array(
	            'title',
	            'editor',
	            'author',
	            'revisions'
	        )
		];
		
		register_post_type( 'topics', $args );
	}

	function topics_custom_columns( $columns ){
		$newColumns = array();

		$newColumns['title'] = 'Title';
		$newColumns['topic'] = 'Topic Name';
		$newColumns['user'] = 'Writer';
		$newColumns['search_g'] = 'Search in Google';
		$newColumns['copy_title'] = 'Copy Title';
		$newColumns['decision_button'] = 'Status';
		$newColumns['date'] = 'Created Date';

		return $newColumns;
	}

	function topics_custom_column_manage( $column, $post_id ){
		switch( $column ) {

			case 'topic' :
				echo get_subtitle( $post_id );
				break;

			case 'user' :
				$writer_list_value = get_post_meta( $post_id, 'cruiser_writer_value_key', true );
				echo $writer_list_value;
				break;

			case 'search_g' :
				if( !get_subtitle() ){
					echo "Not Found";
				}
				else{
					echo '<button class="btn btn-success btn-sm google-search-button"><a href="https://google.com/search?q=' . urlencode(get_subtitle())  . '" target="_blank">' . get_subtitle() . '</a></button>';
				}
				break;

			case 'copy_title' :
				echo '<input class="copy-to-clip-input" id="clickTitle-' . $post_id . '" type="text" value="' . get_the_title() . '">';

				echo '<button class="btn btn-danger btn-sm" onclick="return copyFunction(' . $post_id . ')">Copy Title</button>';
				break;

			case 'decision_button':
				$topic_checkbox_meta = get_post_meta( $post_id, 'topic_checkbox_value', true );
				$meta = get_post_meta( $post_id );
				$topic_checkbox_value = ( isset( $meta['topic_checkbox_value'][0] ) &&  '1' === $meta['topic_checkbox_value'][0] ) ? 1 : 0;

				if( $topic_checkbox_value == 0 ){
			       	echo '<button class="btn btn-sm btn-info" id="pickButton-' . $post_id . '" onclick="return pickFunction( '.$post_id.' )">Pick</button>';
			    }
			    else {
			        echo '<button class="btn btn-sm btn-outline-primary">Picked</button>';   
			    }
				break;

		}

	}

	function update_post_topic_writer() 
	{
	    if ( isset($_POST) ) {

	        $post_id = $_POST['post_id'];

		    update_post_meta( $post_id, 'topic_checkbox_value', 1 );

			$return = array(
			    'message' => __( 'Saved', 'textdomain' ),
			);
			wp_send_json_success( $return );

	    }
	}


	function topics_custom_filter($query)
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
	        );
	    }
	}


    
}
