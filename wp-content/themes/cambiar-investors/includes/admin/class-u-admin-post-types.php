<?php
/**
 * Post Types Admin
 *
 * @author   Elena ZHyvohliad
 * @category Admin
 * @package  U_Theme/Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'U_Admin_Post_Types' ) ) :

/**
 * U_Admin_Post_Types Class.
 *
 * Handles the edit posts views and some functionality on the edit post screen for U post types.
 */
class U_Admin_Post_Types {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_post_updated_messages' ), 10, 2 );

		// Disable Auto Save
		add_action( 'admin_print_scripts', array( $this, 'disable_autosave' ) );

		// WP List table columns. Defined here so they are always available for events such as inline editing.
		add_filter( 'manage_strategy_posts_columns',    array( $this, 'strategy_columns' ) );
		add_filter( 'posts_clauses', [ $this, 'posts_clauses_with_tax' ], 10, 2 );
		add_action( 'manage_strategy_posts_custom_column',    array( $this, 'render_strategy_columns' ), 2 );
		add_action( 'pre_get_posts', [ $this, 'make_custom_sorting_by_additional_fields' ] );
		add_filter( 'manage_edit-strategy_sortable_columns', array( $this, 'strategy_sortable_columns' ) );
		add_filter( 'bulk_actions-edit-strategy', array( $this, 'remove_edit_bulk_actions' ) );


		// Disable post type view mode options
		add_filter( 'view_mode_post_types', array( $this, 'disable_view_mode_options' ) );

		
		add_action( 'disable_months_dropdown', array( $this, 'disable_months_dropdown' ), 10, 2 );

	}

	/**
	 * Change messages when a post type is updated.
	 * @param  array $messages
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;


		$messages['utheme_strategy'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Strategy updated.', 'utheme' ),
			2 => __( 'Custom field updated.', 'utheme' ),
			3 => __( 'Custom field deleted.', 'utheme' ),
			4 => __( 'Strategy updated.', 'utheme' ),
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Strategy restored to revision from %s', 'utheme' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Strategy updated.', 'utheme' ),
			7 => __( 'Strategy saved.', 'utheme' ),
			8 => __( 'Strategy submitted.', 'utheme' ),
			9 => sprintf( __( 'Strategy scheduled for: <strong>%1$s</strong>.', 'utheme' ),
			  date_i18n( __( 'M j, Y @ G:i', 'utheme' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Strategy draft updated.', 'utheme' ),
			11 => __( 'Strategy updated and email sent.', 'utheme' )
		);

		return $messages;
	}

	/**
	 * Specify custom bulk actions messages for different post types.
	 * @param  array $bulk_messages
	 * @param  array $bulk_counts
	 * @return array
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

		$bulk_messages['strategy'] = array(
			'updated'   => _n( '%s strategy updated.', '%s strategies updated.', $bulk_counts['updated'], 'utheme' ),
			'locked'    => _n( '%s strategy not updated, somebody is editing it.', '%s strategies not updated, somebody is editing them.', $bulk_counts['locked'], 'utheme' ),
			'deleted'   => _n( '%s strategy permanently deleted.', '%s strategies permanently deleted.', $bulk_counts['deleted'], 'utheme' ),
			'trashed'   => _n( '%s strategy moved to the Trash.', '%s strategies moved to the Trash.', $bulk_counts['trashed'], 'utheme' ),
			'untrashed' => _n( '%s strategy restored from the Trash.', '%s strategies restored from the Trash.', $bulk_counts['untrashed'], 'utheme' ),
		);

		return $bulk_messages;
	}

	/**
	 * Define custom columns for strategies.
	 * @param  array $existing_columns
	 * @return array
	 */
	public function strategy_columns( $existing_columns ) {
		$columns = array();

		foreach ($existing_columns as $key => $label ){
            $columns[$key] =  $label;
            if( $key == 'title'){
                $columns['ticker'] = __( 'Ticker', 'utheme' );
                $columns['type']   = __( 'Type', 'utheme' );
            }
        }

		return $columns;
	}

	/**
	 * Output custom columns for strategies.
	 * @param string $column
	 */
	public function render_strategy_columns( $column ) {
		global $post, $the_strategy;

        if ( ! is_object( $the_strategy ) || $the_strategy->id != $post->ID ) {
            $the_strategy = u_get_strategy( $post->ID );
        }

		switch ( $column ) {
			case 'type' :
                $t = u_get_strategy_types();
                echo $the_strategy->type ? $t[$the_strategy->type] : '&dash;';
				break;
			default :
				echo $the_strategy->$column ? $the_strategy->$column : '&dash;';
			break;
		}
	}
	
	/**
	 * Makes sorting by custom columns
	 *
	 * @param WP_Query $query
	 */
	public function make_custom_sorting_by_additional_fields( WP_Query $query ) {
		if ( ! is_admin() ) {
			return;
		}
		
		if ( 'strategy' == $query->get( 'post_type' ) ) {
			switch ( $query->get( 'orderby' ) ) {
				case 'ticker':
					$query->set( 'orderby', 'meta_value' );
					$query->set( 'meta_key', '_ticker' );
					break;
				case 'type':
					$query->set( 'orderby', 'meta_value' );
					$query->set( 'meta_key', '_type' );
					break;
			}
		}
	}
	
	public function posts_clauses_with_tax( $clauses, WP_Query $query ) {
		if ( ! is_admin() ) {
			return;
		}
		
		global $wpdb;
		
		if ( 'strategy' == $query->get( 'post_type' ) ) {
			switch ( $query->get( 'orderby' ) ) {
				case 'strategy_category':
				case 'strategy_group':
				case 'share_class':
				case 'geography':
					$clauses['join'] .= "
						LEFT OUTER JOIN {$wpdb->term_relationships} AS rel2 ON {$wpdb->posts}.ID = rel2.object_id
						LEFT OUTER JOIN {$wpdb->term_taxonomy} AS tax2 ON rel2.term_taxonomy_id = tax2.term_taxonomy_id
						LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
					";
					$clauses['where'] .= " AND (taxonomy = '{$query->get( 'orderby' )}' OR taxonomy IS NULL)";
					$clauses['groupby'] = "rel2.object_id";
					$clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
					$clauses['orderby'] .= strtoupper( $query->get('order') );
					break;
			}
		}
		
		return $clauses;
	}

	/**
	 * Make columns sortable
	 *
	 * @param  array $columns
	 * @return array
	 */
	public function strategy_sortable_columns( $columns ) {
		$custom = [
			'type'                       => 'type',
			'ticker'                     => 'ticker',
			'taxonomy-strategy_category' => 'strategy_category',
			'taxonomy-strategy_group'    => 'strategy_group',
			'taxonomy-share_class'       => 'share_class',
			'taxonomy-geography'         => 'geography',
		];
		
		return wp_parse_args( $custom, $columns );
	}



	/**
	 * Remove edit from the bulk actions.
	 *
	 * @param array $actions
	 * @return array
	 */
	public function remove_edit_bulk_actions( $actions ) {

		if ( isset( $actions['edit'] ) ) {
			unset( $actions['edit'] );
		}		

		return $actions;
	}


	/**
	 * Disable the auto-save functionality for athlete.
	 */
	public function disable_autosave() {
		global $post;

		if ( $post && get_post_type( $post->ID ) == 'strategy' ) {
			wp_dequeue_script( 'autosave' );
		}
	}


	public function disable_months_dropdown($disable, $post_type)
	{
		switch ($post_type) {
			case 'strategy':
				$disable = true;
				break;
		}
		return $disable;
	}

	/**
	 *
	 * @param  array $post_types Array of post types supporting view mode
	 * @return array             Array of post types supporting view mode
	 */
	public function disable_view_mode_options( $post_types ) {
		unset( $post_types['strategy'] );
		return $post_types;
	}


}

endif;

new U_Admin_Post_Types();
