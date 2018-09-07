<?php
/**
 * Template Name: Full Page
 *
 * @package		U_Theme/Template
 * @author 		uCAT
 */

get_header(); ?>
<?php
// check if the flexible content field has rows of data
if( have_rows('u_section_elements') ):
     // loop through the rows of data
    while ( have_rows('u_section_elements') ) : the_row();
	
	    $bg     = get_sub_field( 'u_section_background' );
	    $id     = get_sub_field( 'u_section_css_id' );
	    $class     = get_sub_field( 'u_section_css_class' );
	    $layout = get_row_layout();
	    // Load block template from blocks directory
	    $layoutName = str_replace( '_', '-', $layout );

        if (!$blockPath = locate_template('template-parts/blocks/' . $layoutName . '.php')) {

            trigger_error(sprintf(__('Block template %s missing', 'utheme'), $layoutName), E_USER_ERROR);
        }
        
        require $blockPath;

    endwhile;

else :

    // no layouts found

endif;

?>
<?php get_footer();
