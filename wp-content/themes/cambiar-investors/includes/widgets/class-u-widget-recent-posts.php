<?php
/**
 * Widget API: U_Widget_Recent_Posts class
 *
 * @package U_Theme
 * @subpackage Widgets
 * @since 1.2.0
 */

/**
 * Core class used to implement a Recent Posts widget. *
 *
 * @see WP_Widget_Recent_Posts
 * @see WP_Widget
 */
class U_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
     *
     * @global WP_Post $post
	 */
	public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		
		add_filter( 'posts_join', [ U_Theme_Setup::class, 'posts_join' ], 10, 2 );
		add_filter( 'posts_where', [ U_Theme_Setup::class, 'posts_where' ], 10, 2 );
		add_filter( 'posts_groupby', [ U_Theme_Setup::class, 'posts_groupby' ], 10, 2 );

        /**
         * Filters the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         * @since 4.9.0 Added the `$instance` parameter.
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args     An array of arguments used to retrieve the recent posts.
         * @param array $instance Array of settings for the current widget.
         */
        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ), $instance ) );

        if ( ! $r->have_posts() ) {
            return;
        }
        ?>
        <?php echo $args['before_widget']; ?>
        <?php
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <ul class="sidebar-post-list">
            <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <?php
                $post_title = get_the_title();
                $title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                ?>
                <li>
                    <div class="text">
                        <div class="meta">
                            <?php if ( 'page' == get_post_type() && ( $term = get_term( get_field( 'put_to_the_category' ) ) ) ) : ?>
                                <a href="<?php echo get_term_link( $term->term_id ); ?>" rel="category tag"><?php echo $term->name; ?></a>
                            <?php else : ?>
                                <?php echo get_the_category_list( ', ' ); ?>
                            <?php endif; ?>
                            
                            <?php if ( $show_date ) : ?>
                                - <?php the_time('j M Y '); ?>
                            <?php endif; ?>
                        </div>
                        <div class="title"><a href="<?php the_permalink(); ?>"><?php echo $title; ?></a></div>
                    </div>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="image">
                            <?php the_post_thumbnail( 'post-thumbnail-small'); ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endwhile; $r->reset_postdata(); ?>
        </ul>
		<?php
		echo $args['after_widget'];
		
		remove_filter( 'posts_join', [ U_Theme_Setup::class, 'posts_join' ], 10, 2 );
		remove_filter( 'posts_where', [ U_Theme_Setup::class, 'posts_where' ], 10, 2 );
		remove_filter( 'posts_groupby', [ U_Theme_Setup::class, 'posts_groupby' ], 10, 2 );
	}

}
