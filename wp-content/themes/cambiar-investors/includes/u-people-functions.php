<?php
/**
 *
 * @version   1.0.0
 * @package   U_Theme/Functions
 * @category  Functions
 * @author    Elena Zhyvohliad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Retrieves terms of the taxonomy department
 *
 * @param array $args
 *
 * @return array
 */
function u_get_people_departments( $args = [] ) {
	$items = array();
	
	$defaults = [
		'taxonomy'   => 'department',
		'hide_empty' => true,
		'orderby'    => 'id',
		'order'      => 'ASC',
	];
	
	$args = wp_parse_args( $args, $defaults );
	
	
	if ( $terms = get_terms( $args ) ) {
		foreach ( $terms as $t ) {
			$items[ $t->term_id ] = $t->name;
		}
	}
	
	return $items;
}

/**
 * Retrieves array of posts of the custom post type People
 *
 * @param array $args
 *
 * @return array
 */
function u_get_managers($args = []){
    $defaults = array(
        'numberposts' => 10,
        'category'    => 0,
        'orderby'     => 'title',
        'order'       => 'DESC',
        'include'     => array(),
        'exclude'     => array(),
        'meta_key'    => '',
        'meta_value'  =>'',
        'suppress_filters' => true
    );
    $r = wp_parse_args( $args, $defaults );
    $r['post_type'] = 'people';

    if ( empty( $r['post_status'] ) )
        $r['post_status'] = 'publish';
    if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
        $r['posts_per_page'] = $r['numberposts'];
	
	if ( ! empty( $r['department'] ) ) {
		$r['tax_query'][] = [
			'taxonomy' => 'department',
			'field'    => 'term_id',
			'terms'    => $r['department'],
		];
	}
	
    if ( ! empty($r['include']) ) {
        $incposts = wp_parse_id_list( $r['include'] );
        $r['posts_per_page'] = count($incposts);  // only the number of posts included
        $r['post__in'] = $incposts;
    } elseif ( ! empty($r['exclude']) )
        $r['post__not_in'] = wp_parse_id_list( $r['exclude'] );

    $r['ignore_sticky_posts'] = true;
    $r['no_found_rows'] = true;

    $get_posts = new WP_Query;

    $posts = $get_posts->query($r);

    return array_filter($posts);
}

function u_get_managers_list($args = []){
    $managers = u_get_managers($args);
    $list =[];
    if( $managers ){
        foreach ($managers as $manager ){
            $list[$manager->ID] = $manager->post_title;
        }
    }

    return $list;
}


/**
 * Main function for returning manager, uses the U_Manager_Factory class.
 *
 * @param  mixed $the_manager Post object or post ID of the strategy.
 * @return U_Manager
 */
function u_get_manager( $the_manager = false ) {
    if ( ! did_action( 'utheme_init' ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'utheme_get_strategy should not be called before the utheme_init action.', 'utheme' ), '1.0.0' );
        return false;
    }
    return utheme()->manager_factory->get_manager( $the_manager);
}