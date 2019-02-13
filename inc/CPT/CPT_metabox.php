<?php 

/**
 * @package  CruiserPlugin
 */
namespace Inc\CPT;

class CPT_metabox
{

    public function register() {
        // To Stop from Saving draft in Topics field
        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_enqueue_scripts' ) );

        // Add Metaboxes to Topics CPT 
        add_action( 'add_meta_boxes', array($this, 'cruiser_meta_box_callback' ) );
        // Save Metabox value on Saving Post
        add_action( 'save_post' , array( $this, 'cruiser_save_topic_writer' ) );
    }

    function my_admin_enqueue_scripts() {
        if ( 'topics' == get_post_type() )
            wp_dequeue_script( 'autosave' );
    }


    function cruiser_meta_box_callback( $post ){
        add_meta_box('cruiser_author_meta_box', 'Select Writer', array( $this, 'cruiser_author_meta_box_callback' ), 'topics', 'side' , 'high');
        add_meta_box( 'cruiser_topic_pick_box', 'Pick Topic', array( $this, 'cruiser_topic_pick_box_callback' ), 'topics', 'side', 'high' );

    }

    function cruiser_author_meta_box_callback( $post ){
        wp_nonce_field( 'cruiser_save_topic_writer', 'cruiser_save_topic_writer_nonce' );

        $writer_value = get_post_meta( $post->ID, 'cruiser_writer_value_key', true );
        $all_users = get_users();
        ?>
        <label>Assign To Writer :  </label>
        <br>
        
        <select name="cruiser_writer_field_name" id="cruiser_writer_field_name" required>
            <option value="" >Choose...</option>
        <?php 
            foreach( $all_users as $user ){
                echo '<option value="' . esc_html($user->display_name) .'" '. selected( $writer_value, esc_html($user->display_name) ) .'>' . esc_html($user->display_name) . '</option>';
            }
        ?>
        </select>
        <?php
    }


    function cruiser_topic_pick_box_callback( $post ) {
        $meta = get_post_meta( $post->ID );
        $topic_checkbox_value = ( isset( $meta['topic_checkbox_value'][0] ) &&  '1' === $meta['topic_checkbox_value'][0] ) ? 1 : 0;
        wp_nonce_field( 'topic_checkbox_value', 'topic_checkbox_value' ); 
        ?>
        <input type="checkbox" name="topic_checkbox_value" id="topic_checkbox_value-<?php echo $post->ID; ?>" value="1" <?php checked( $topic_checkbox_value, 1 ); ?> style="display: none;"/>
        <?php

        if( $topic_checkbox_value  == 0){
            echo '<button class="btn btn-sm btn-info" id="click-btn" onclick="checkedFunction(' . $post->ID .')">Pick</button>';
        }
        else {
            echo '<button class="btn btn-sm btn-warning">Picked</button>';
            
        }
    }


    function cruiser_save_topic_writer( $post_id )
    {
        if( !isset( $_POST['cruiser_save_topic_writer_nonce'] ) ){
         return;
        }

        if( !wp_verify_nonce( $_POST['cruiser_save_topic_writer_nonce'], 'cruiser_save_topic_writer' ) ){
         return;
        }

        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
         return;
        }

        if( !current_user_can( 'edit_post', $post_id ) ){
         return;
        }

        if( !isset( $_POST['cruiser_writer_field_name'] ) ){
         return;
        }

        $topic_user = $_POST['cruiser_writer_field_name'];

        update_post_meta( $post_id, 'cruiser_writer_value_key', $topic_user );

        $topic_checkbox_value = ( isset( $_POST['topic_checkbox_value'] ) && '1' === $_POST['topic_checkbox_value'] ) ? 1 : 0;
        update_post_meta( $post_id, 'topic_checkbox_value', esc_attr( $topic_checkbox_value ) );

    }

}
