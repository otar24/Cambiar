<?php
/**
 * Meta Boxes
 *
 * Registers metaboxes.
 *
 * @class     U_Admin_Meta_Boxes
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    Elena Zhyvohliad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * U_Admin_Meta_Boxes.
 */
class U_Admin_Meta_Boxes {

	/**
	 * Is meta boxes saved once?
	 *
	 * @var boolean
	 */
	private static $saved_meta_boxes = false;

	/**
	 * Meta box error messages.
	 *
	 * @var array
	 */
	public static $meta_box_errors  = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

		/**
		 * Save strategy Meta Boxes.
		 *
		 * In strategy:
		 *      Save strategy data - also updates status and sends out admin emails if needed. Last to show latest data.
		 *      Save actions - sends out other emails. Last to show latest data.
		 */
		add_action( 'utheme_process_strategy_meta', 'U_Meta_Box_Strategy_Data::save', 40, 2 );

		// Error handling (for showing errors from meta boxes on next page load)
		add_action( 'admin_notices', array( $this, 'output_errors' ) );
		add_action( 'shutdown', array( $this, 'save_errors' ) );
	}

	/**
	 * Add an error message.
	 * @param string $text
	 */
	public static function add_error( $text ) {
		self::$meta_box_errors[] = $text;
	}

	/**
	 * Save errors to an option.
	 */
	public function save_errors() {
		update_option( 'utheme_meta_box_errors', self::$meta_box_errors );
	}

	/**
	 * Show any stored error messages.
	 */
	public function output_errors() {
		$errors = maybe_unserialize( get_option( 'utheme_meta_box_errors' ) );

		if ( ! empty( $errors ) ) {

			echo '<div id="utheme_errors" class="error notice is-dismissible">';

			foreach ( $errors as $error ) {
				echo '<p>' . wp_kses_post( $error ) . '</p>';
			}

			echo '</div>';

			// Clear
			delete_option( 'utheme_meta_box_errors' );
		}
	}


	public function edit_form_after_title (){
        global $post, $wp_meta_boxes;
        do_meta_boxes(get_current_screen(), 'advanced', $post);
        unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }
	/**
	 * Add Meta boxes.
	 */
	public function add_meta_boxes() {

		// Strategy
		foreach ( array('strategy') as $type ) {
            add_meta_box( 'utheme-strategy-data', __( 'Overview', 'utheme' ), 'U_Meta_Box_Strategy_Data::output', $type, 'advanced', 'high' );
            add_meta_box( 'utheme-strategy-perfomance', __( 'Perfomance', 'utheme' ), 'U_Meta_Box_Strategy_Data::output_perfomance', $type, 'advanced', 'high' );
            add_meta_box( 'utheme-strategy-composition', __( 'Composition', 'utheme' ), 'U_Meta_Box_Strategy_Data::output_composition', $type, 'advanced', 'high' );
            add_meta_box( 'utheme-strategy-cap-gains', __( 'Cap gains & income', 'utheme' ), 'U_Meta_Box_Strategy_Data::output_cap_gains', $type, 'advanced', 'high' );

		}

	}


	/**
	 * Remove bloat.
	 */
	public function remove_meta_boxes() {
		foreach ( array('strategy', 'people') as $type ) {
			remove_meta_box( 'commentsdiv', $type, 'normal' );
			remove_meta_box( 'commentstatusdiv', $type, 'normal' );
			remove_meta_box( 'tagsdiv-strategy_category', $type, 'side' );
			remove_meta_box( 'tagsdiv-strategy_group', $type, 'side' );
			remove_meta_box( 'tagsdiv-share_class', $type, 'side' );
			remove_meta_box( 'tagsdiv-geography', $type, 'side' );
		}
	}

	/**
	 * Check if we're saving, the trigger an action based on the post type.
	 *
	 * @param  int $post_id
	 * @param  object $post
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
			return;
		}

		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce
		if ( empty( $_POST['utheme_meta_nonce'] ) || ! wp_verify_nonce( $_POST['utheme_meta_nonce'], 'utheme_save_data' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check user has permission to edit
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		self::$saved_meta_boxes = true;

		// Check the post type
		if ( $post->post_type == 'strategy' ) {
			do_action( 'utheme_process_'.$post->post_type.'_meta', $post_id, $post );
		}

	}

}

new U_Admin_Meta_Boxes();
