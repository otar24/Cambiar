<?php
/**
 * Widget API: U_Widget_Related_Posts class
 *
 * @package U_Theme
 * @subpackage Widgets
 * @since 1.2.0
 */

/**
 * Core class used to implement a Related Posts widget. *
 *
 * @see WP_Widget
 */
class U_Widget_Related_Posts extends WP_Widget {

	/**
	 * Sets up a new Related Posts widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_related_entries',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'related-posts', __( 'Related Posts' ), $widget_ops );
		$this->alt_option_name = 'widget_related_entries';
	}

	/**
	 * Outputs the content for the current Related Posts widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Related Posts widget instance.
     *
     * @global WP_Post $post
	 */
	public function widget( $args, $instance ) {
	    global $post;
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Related Posts' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the Related Posts widget.
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args     An array of arguments used to retrieve the recent posts.
		 * @param array $instance Array of settings for the current widget.
		 */
        $p_cat = wp_get_post_categories( $post->ID );
		$r = new WP_Query( array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post__not_in'        => [$post->ID],
            'cat'                 => implode(',', $p_cat)
		));

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
			<?php foreach ( $r->posts as $related_post ) : ?>
				<?php
				$post_title = get_the_title( $related_post->ID );
				$title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
				$category   = get_the_category_list( ', ', '',  $related_post->ID );
				?>
				<li>
                    <div class="text">
                        <div class="meta">
                            <?php echo $category; ?>
                            <?php if ( $show_date ) : ?>
                                - <?php the_time('j M Y '); ?>
                            <?php endif; ?>
                        </div>
                        <div class="title"><a href="<?php the_permalink( $related_post->ID ); ?>"><?php echo $title; ?></a></div>
                    </div>
                    <div class="image">
                        <?php echo get_the_post_thumbnail($related_post->ID, 'post-thumbnail-small'); ?>
                    </div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating the settings for the current Related Posts widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Related Posts widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}
