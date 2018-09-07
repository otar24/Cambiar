<?php
/**
 * Strategy Functions
 *
 * Functions for strategy specific things.
 *
 * @version   1.0.0
 * @package   U_Theme/Functions
 * @category  Functions
 * @author    Elena Zhyvohliad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function u_get_strategies($args = array()){
    $defaults = array(
        'numberposts' => -1,
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
    $r['post_type'] = 'strategy';

    if ( empty( $r['post_status'] ) )
        $r['post_status'] = 'publish';
    if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
        $r['posts_per_page'] = $r['numberposts'];
    if ( ! empty($r['category']) ){
        if( !isset( $r['tax_query'] ) ){
            $r['tax_query'] = array();
        }
        $r['tax_query'][] = array(
            'taxonomy' => 'strategy_category',
            'field'    => 'term_id',
            'terms'    => $r['category'],
        );
    }
    if ( ! empty($r['share_class']) ){
        if( !isset( $r['tax_query'] ) ){
            $r['tax_query'] = array();
        }
        $r['tax_query'][] = array(
            'taxonomy' => 'share_class',
            'field'    => 'term_id',
            'terms'    => $r['share_class'],
        );
    }
    if ( ! empty($r['geography']) ){
        if( !isset( $r['tax_query'] ) ){
            $r['tax_query'] = array();
        }
        $r['tax_query'][] = array(
            'taxonomy' => 'geography',
            'field'    => 'term_id',
            'terms'    => $r['share_class'],
        );
    }
    if ( ! empty($r['group']) ){
        if( !isset( $r['tax_query'] ) ){
            $r['tax_query'] = array();
        }
        $r['tax_query'][] = array(
            'taxonomy' => 'strategy_group',
            'field'    => 'term_id',
            'terms'    => $r['group'],
        );
    }
    if ( ! empty($r['type']) ){
        if( !isset( $r['meta_query'] ) ){
            $r['meta_query'] = array();
        }
        $r['meta_query'][] = array(
            'key'      => '_type',
            'value'    => $r['type'],
            'compare'  => '=',
        );
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

    return array_filter( array_map( 'u_get_strategy', $posts ) );
}

/**
 * Get all strategy options.
 *
 * @return array
 */
function u_get_strategy_types() {
	$types = array(
        'mutual_funds'          => _x( 'Mutual funds', 'Strategy type', 'utheme' ),
        'managed_accounts'      => _x( 'Separate accounts', 'Strategy type', 'utheme' ),

	);
	return $types;
}

function u_get_strategy_share_classes($hide_empty = false){
    $classes = array();
    $terms = get_terms( array(
        'taxonomy' => 'share_class',
        'hide_empty' => $hide_empty,
    ) );
    if($terms){
        foreach($terms as $t){
            $classes[$t->term_id] = $t->name;
        }
    }
    return $classes;
}

function u_get_strategy_geography_items($hide_empty = false){
    $items = array();
    $terms = get_terms( array(
        'taxonomy' => 'geography',
        'hide_empty' => $hide_empty,
    ) );
    if($terms){
        foreach($terms as $t){
            $items[$t->term_id] = $t->name;
        }
    }
    return $items;
}

function u_get_strategy_categories($hide_empty = false){
    $items = array();
    $terms = get_terms( array(
        'taxonomy' => 'strategy_category',
        'hide_empty' => $hide_empty,
    ) );
    if($terms){
        foreach($terms as $t){
            $items[$t->term_id] = $t->name;
        }
    }
    return $items;
}

function u_get_strategy_groups($hide_empty = false){
    $items = array();
    $terms = get_terms( array(
        'taxonomy' => 'strategy_group',
        'hide_empty' => $hide_empty,
    ) );
    if($terms){
        foreach($terms as $t){
            $items[$t->term_id] = $t->name;
        }
    }
    return $items;
}

function u_get_groups_tickers($group_id = null, $type){
    if( is_null($group_id)) return false;
    //$strategies = u_get_strategies
}



/**
 * Main function for returning strategys, uses the U_Strategy_Factory class.
 *
 * @param  mixed $the_strategy Post object or post ID of the strategy.
 * @return U_Strategy
 */
function u_get_strategy( $the_strategy = false ) {
	if ( ! did_action( 'utheme_init' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'utheme_get_strategy should not be called before the utheme_init action.', 'utheme' ), '1.0.0' );
		return false;
	}
	return utheme()->strategy_factory->get_strategy( $the_strategy );
}



