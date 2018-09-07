<?php
/**
 * Theme Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		uCAT
 * @category 	Core
 * @package 	U_Theme/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core functions (available in both admin and frontend).
include( 'u-strategy-functions.php' );
include( 'u-people-functions.php' );
include( 'u-frontend-functions.php' );



if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 * @return bool
	 * @since  1.0.1
	 */
	function is_ajax() {
		return defined( 'DOING_AJAX' );
	}
}

/**
 * Queue some JavaScript code to be output in the footer.
 *
 * @param string $code
 */
function u_enqueue_js( $code ) {
    global $u_queued_js;

    if ( empty( $u_queued_js ) ) {
        $u_queued_js = '';
    }

    $u_queued_js .= "\n" . $code . "\n";
}

/**
 * Output any queued javascript code in the footer.
 */
function u_print_js() {
    global $u_queued_js;

    if ( ! empty( $u_queued_js ) ) {
        // Sanitize.
        $u_queued_js = wp_check_invalid_utf8( $u_queued_js );
        $u_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $u_queued_js );
        $u_queued_js = str_replace( "\r", '', $u_queued_js );

        $js = "<!-- U_THEME JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $u_queued_js });\n</script>\n";

        /**
         * utheme_queued_js filter.
         *
         * @param string $js JavaScript code.
         */
        echo apply_filters( 'utheme_queued_js', $js );

        unset( $u_queued_js );
    }
}

/**
 * Checks to see if we're on the homepage or not.
 */
function u_is_frontpage() {
    return ( is_front_page() && ! is_home() );
}

function u_get_assets_uri($file = ''){
     return get_theme_file_uri('/assets/' . $file) ;
}

if ( ! function_exists( 'u_edit_link' ) ) :
    /**
     * Returns an accessibility-friendly link to edit a post or page.
     *
     * This also gives us a little context about what exactly we're editing
     * (post or page?) so that users understand a bit more where they are in terms
     * of the template hierarchy and their content. Helpful when/if the single-page
     * layout with multiple posts/pages shown gets confusing.
     */
    function u_edit_link() {
        edit_post_link(
            sprintf(
            /* translators: %s: Name of current post */
                __( 'Edit<span class="screen-reader-text"> %s</span>', 'utheme' ),
                get_post_type()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @param string|array $var
 * @return string|array
 */
function u_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'u_clean', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}


function u_number_format($v, $precision = 2, $dec_point = '.', $thousands_stap = ','){
    return number_format( floatval($v), $precision, $dec_point, $thousands_stap );
}
function u_price_format($v, $precision = 2){
    return '$'. u_get_short_number_format($v, $precision);
}

function u_percent_format($v, $precision = 2){
    $n_format = number_format( floatval($v), $precision);
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . '%';
}

/**
 * @param $n
 * @return string
 * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
 */
function u_get_short_number_format( $n , $precision = 2, $dec_point = '.', $thousands_stap = ',' ) {
    $n = floatval($n);
    $amount = $n;
	$suffix = '';
	if  ( $n >= 1000000 ) {
	    $amount = $n / 1000000;
        $suffix = ' MM';
    }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 && $n >= 1000000) {
    	$amount = preg_replace('/\.?0+$/', '', $amount );
    }
    
	return number_format($amount, $precision, $dec_point, $thousands_stap ) . $suffix;
}

function u_get_colors(){
    return ["#000000","#00233e", "#00457c", "#205c8c", "#40749d", "#608bad", "#c2d1de", "#9fb9ce", "#bfd1de", "#dfe8ef", '#d1d5d7', '#a2aaaf', '#d0d4d7' ];
}

function u_luminance($hexcolor, $percent)
{
    if ( strlen( $hexcolor ) < 6 ) {
        $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
    }

    $hexcolor = array_slice( array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) ), 0, 3) ;

    foreach ($hexcolor as $i => $color) {
        $from = $percent < 0 ? 0 : $color;
        $to = $percent < 0 ? $color : 255;
        $pvalue = ceil( ($to - $from) * $percent );
        $hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
    }
    return '#' . implode($hexcolor);
}

function u_get_posts($args = []){
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
    $r['post_type'] = 'post';

    if ( empty( $r['post_status'] ) )
        $r['post_status'] = 'publish';
    if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
        $r['posts_per_page'] = $r['numberposts'];

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

    //return array_filter( array_map( 'u_get_strategy', $posts ) );
}

function contactButton() {
    if (get_field('general_show_contact_button', 'options')) :
        echo '<a href="#" class="btn-contact btn btn-primary text-uppercase cd-popup-trigger" data-popup-id="contact-popup">'.  esc_attr(get_field('general_button_text', 'options')) .'</a>';
    endif;
}