<?php

function u_get_container_id(){
	$template = get_page_template_slug();
	$id       = get_field( 'u_css_id' );
	
	if ( ! empty( $id ) ) {
		return $id;
	}
	
	if ( ( is_page() && $template === 'template-full-page.php' ) || is_singular( 'strategy' ) ) {
		return 'fullpage';
	}
	if ( is_page() && ( $template === 'template-full-page-parallax.php' || $template === 'template-history.php' ) ) {
		return 'fullpage-parallax';
	}
	
	return 'main';
}

function u_get_container_class(){
    $class[] = get_field('u_css_class');

    if( is_single() || is_post_type_archive('strategy') || is_blog_page() ){
        $class[] = 'no-autoscroll';
    }
    if(is_singular('strategy') ){
        $class[] = 'fullpage-wrapper';
    }
    
    return implode(' ', array_filter( $class ) );
}

if ( ! function_exists( 'utheme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function utheme_posted_on() {

	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'by %s', 'utheme' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="posted-on">' . utheme_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;


if ( ! function_exists( 'utheme_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function utheme_time_link() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="screen-reader-text">Posted on</span> %s', 'utheme' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}
endif;


if ( ! function_exists( 'utheme_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function utheme_entry_footer() {

	/* translators: used between list items, there is a space after the comma */
	$separate_meta = __( ' ', 'utheme' );

	// Get Categories for posts.
	#$categories_list = get_the_category_list( $separate_meta );
    $categories_list = false;

	// Get Tags for posts.
	$tags_list = get_the_tag_list( '', $separate_meta );

	// We don't want to output .entry-footer if it will be empty, so make sure its not.
	if ( ( ( utheme_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

		echo '<footer class="post-meta">';

			if ( 'post' === get_post_type() ) {
                ?>
                <div class="date">
                    <time class="entry-date published updated" datetime="<?php the_time('c'); ?>"><?php the_time('j M Y g:i A ') ?></time>
                </div>
                <?php
				if ( ( $categories_list && utheme_categorized_blog() ) || $tags_list ) {
					echo '<span class="cat-tags-links">';

						// Make sure there's more than one category before displaying.
						if ( $categories_list && utheme_categorized_blog() ) {
                            ?>
                            <div class="tags-links">
                                <?php echo $categories_list; ?>
                            </div>
                            <?php
						}

						if ( $tags_list && ! is_wp_error( $tags_list ) ) {
							?>
                            <div class="tags-links">
                                <?php echo $tags_list; ?>
                            </div>
                            <?php
						}

					echo '</span>';
				}
			}

			utheme_edit_link();

		echo '</footer> <!-- .entry-footer -->';
	}
}
endif;


if ( ! function_exists( 'utheme_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function utheme_edit_link() {
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
 * Display a front page section.
 *
 * @param WP_Customize_Partial $partial Partial associated with a selective refresh request.
 * @param integer              $id Front page section to display.
 */
function utheme_front_page_section( $partial = null, $id = 0 ) {
	if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
		// Find out the id and set it up during a selective refresh.
		global $uthemecounter;
		$id = str_replace( 'panel_', '', $partial->id );
		$uthemecounter = $id;
	}

	global $post; // Modify the global post object before setting up post data.
	if ( get_theme_mod( 'panel_' . $id ) ) {
		$post = get_post( get_theme_mod( 'panel_' . $id ) );
		setup_postdata( $post );
		set_query_var( 'panel', $id );

		get_template_part( 'template-parts/page/content', 'front-page-panels' );

		wp_reset_postdata();
	} elseif ( is_customize_preview() ) {
		// The output placeholder anchor.
		echo '<article class="panel-placeholder panel utheme-panel utheme-panel' . $id . '" id="panel' . $id . '"><span class="utheme-panel-title">' . sprintf( __( 'Front Page Section %1$s Placeholder', 'utheme' ), $id ) . '</span></article>';
	}
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function utheme_categorized_blog() {
	$category_count = get_transient( 'utheme_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'utheme_categories', $category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}


/**
 * Flush out the transients used in utheme_categorized_blog.
 */
function utheme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'utheme_categories' );
}
add_action( 'edit_category', 'utheme_category_transient_flusher' );
add_action( 'save_post',     'utheme_category_transient_flusher' );


/**
 * WordPress' missing is_blog_page() function.  Determines if the currently viewed page is
 * one of the blog pages, including the blog home page, archive, category/tag, author, or single
 * post pages.
 *
 * @return bool
 */
function is_blog_page() {

    global $post;
    
    //Check all blog-related conditional tags, as well as the current post type,
    //to determine if we're viewing a blog page.
    return ( is_home() || is_category() || is_post_type_archive( [ 'post' ] ) || is_singular( 'post' ) )
        ? true
        : false ;

}

function utheme_get_svg($args=[]){
    $icon = '';
    if( isset($args['icon'])){
        $icon = '<i class="icon-'.$args['icon'].'"></i>';
    }
    return $icon;
}

function u_fp_auto_height($slug = null){
    if( is_null($slug)){
        global $post;
        $slug = $post->post_name;
    }
    $pages = ['culture'];
    echo in_array($slug, $pages) ? 'fp-auto-height' : '';
}