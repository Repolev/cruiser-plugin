<?php 
/**
* CruiserPlugin
*/
class Subtitle
{
	/**
	 *
	 * Constructor
	 *
	 * @access public
	 **/
	public function __construct()
	{
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_post_type_support( 'topics', 'subtitle' );
		load_plugin_textdomain( 'hc-subtitle', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	/**
	 * Add subtitle field
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function edit_form_after_title()
	{
		global $post;
		if ( isset( $_GET['post_type'] ) )
			$post_type = sanitize_text_field( $_GET['post_type'] );
		elseif ( isset( $post->post_type ) )
			$post_type = $post->post_type;
		else
			$post_type = 'post';
		if ( post_type_supports( $post_type, 'subtitle' ) )
			$this->subtitle_field( $post );
	}
	/**
	 * Enqueue Styles
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	
	/**
	 * Get Subtitle
	 *
	 * @static
	 * @access public
	 * @param int $post_id Post ID
	 * @return str Subtitle
	 * @author Ralf Hortt
	 **/
	public static function get_subtitle( $post_id = FALSE )
	{
		$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();
		return esc_html( apply_filters( 'the_subtitle', get_post_meta( $post_id, '_subtitle', TRUE ) ) );
	}
	/**
	 *
	 * Save subtitle
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( !isset( $_POST['save-subtitle'] ) || !wp_verify_nonce( $_POST['save-subtitle'], plugin_basename( __FILE__ ) ) )
			return;
		if ( '' != $_POST['hc-subtitle'] ) :
			update_post_meta( $post_id, '_subtitle', sanitize_text_field( $_POST['hc-subtitle'] ) );
		else :
			delete_post_meta( $post_id, '_subtitle' );
		endif;
	}
	/**
	 * Metabox content
	 *
	 * @access public
	 * @author Ralf Hortt
	 */
	public function subtitle_field( $post )
	{
		$subtitle = $this->get_subtitle( $post->ID );
		?>
		<input type="text" autocomplete="off" id="hc-subtitle" value="<?php echo esc_attr( $subtitle ) ?>" name="hc-subtitle" placeholder="<?php _e( 'Enter Topic Name', 'hc-subtitle' ); ?>">
		<div id="datafetch"></div>
		<?php
		wp_nonce_field( plugin_basename( __FILE__ ), 'save-subtitle' );
	}
	/**
	 * Display Subtitle
	 *
	 * @static
	 * @access public
	 * @param str $before Before the subtitle
	 * @param str $after After the subtitle
	 * @author Ralf Hortt
	 */
	public static function the_subtitle( $before = '', $after = '' )
	{
		$subtitle = get_subtitle( get_the_ID() );
		if ( '' != $subtitle )
			echo $before . $subtitle . $after;
	}
}
new Subtitle();
/**
 * Getter: Subtitle
 *
 * @param int $post_id Post ID
 * @return str Subtitle
 * @author Ralf Hortt
 **/
function get_subtitle( $post_id = FALSE )
{
	return Subtitle::get_subtitle( $post_id );
}
/**
 * Conditional Tag: Subtitle
 *
 * @param int $post_id Post ID
 * @return bool
 * @author Ralf Hortt
 **/
function has_subtitle( $post_id = FALSE )
{
	if ( '' !== Subtitle::get_subtitle( $post_id ) )
		return TRUE;
	else
		return FALSE;
}
/**
 * Template Tag: Display Subtitle
 *
 * @param str $before Before the subtitle
 * @param str $after After the subtitle
 * @author Ralf Hortt
 */
function the_subtitle( $before = '', $after = '' )
{
	echo Subtitle::get_subtitle( $before, $after );
}
// the jQuery Ajax call here
add_action( 'admin_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script type="text/javascript">
    (function($){
        var searchRequest = null;
        jQuery(function () {
            var minlength = 3;
            jQuery("#hc-subtitle").keyup(function () {
                var that = this,
                value = jQuery(this).val();
                
                if (value.length >= minlength ) {
                    if (searchRequest != null) 
                        searchRequest.abort();
                    searchRequest = jQuery.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                            action: 'data_fetch',
                            search_keyword : value
                        },
                        dataType: "html",
                        success: function(data){
                            //we need to check if the value is the same
                            if (value==jQuery(that).val()) {
                                jQuery('#datafetch').html(data);
                            }
                            else{
                                jQuery('#datafetch').html('Topic Not Found');
                            }
                        }
                    });
                }
            });
        });
    }(jQuery));
</script>

<?php
}
// the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){
	// fetching the data as the title of the post
    $the_query = new WP_Query( array( 'posts_per_page' => -1, 's' => esc_attr( $_POST['search_keyword'] ), 'post_type' => array('post', 'topics') ) );
    if( $the_query->have_posts() ){
        while( $the_query->have_posts() ): $the_query->the_post(); ?>
            <h2><a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a></h2>
        <?php endwhile;
        wp_reset_postdata();
    }
    else{
    	// Text if there is not topic found
    	echo "<span class='notopics'>No Topics Found<span>";
    }
    die();
}