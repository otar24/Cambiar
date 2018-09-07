<div class="post-list" <?php echo have_posts() ? 'id="blog-posts-rows"' : ''; ?> >
	<?php
	$sticky_post = null;
	if ( have_posts() ) :
		/* Start the Loop */
		while ( have_posts() ) : the_post();
			$sticky_post = get_the_ID();
			?>
            <article class="post new-post">
                <div class="post-text-wrap">
                    <div class="post-meta">
						<?php if ( 'page' == get_post_type() && ( $term = get_term( get_field( 'put_to_the_category' ) ) ) ) : ?>
                            <a href="<?php echo get_term_link( $term->term_id ); ?>" rel="category tag"><?php echo $term->name; ?></a>
						<?php else : ?>
							<?php echo get_the_category_list( ', ' ); ?>
						<?php endif; ?>
                        <time datetime="<?php the_time('Y-m-d '); ?>"> - <?php the_time('j M Y '); ?></time>
                    </div>
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>

                    <div class="text-box">
						<?php the_excerpt(); ?>
                    </div>
                </div>
                <figure class="img-holder">
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('utheme-thumbnail'); ?>
                    </a>
                </figure>
            </article>
			<?php
			break;
		endwhile;
		?>
        <div class="rows">
            <div>
				<?php
				/**
				 * @var $wp_query WP_Query
				 */
				global $wp_query;
				
				$r = $wp_query;
				$r->set( 'ignore_sticky_posts', 1 );
				$r->set( 'post__not_in', [ $sticky_post ] );
				
				$i = 0;
				while ( $r->have_posts() ) : $r->the_post();
					?>
                    <article class="post">
                        <div class="post-text-wrap">
                            <div class="post-meta">
								<?php if ( 'page' == get_post_type() && ( $term = get_term( get_field( 'put_to_the_category' ) ) ) ) : ?>
                                    <a href="<?php echo get_term_link( $term->term_id ); ?>" rel="category tag"><?php echo $term->name; ?></a>
								<?php else : ?>
									<?php echo get_the_category_list( ', ' ); ?>
								<?php endif; ?>
                                <time datetime="<?php the_time('Y-m-d '); ?>"> - <?php the_time('j M Y '); ?></time>
                            </div>
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
							<?php if ( $excerpt = get_the_excerpt() ) : ?>
                                <div class="text-box">
									<?php echo apply_filters( 'the_content', wp_trim_words( $excerpt, 25 ) ); ?>
                                </div>
							<?php endif; ?>
                        </div>
                        <figure class="img-holder">
                            <a href="<?php the_permalink(); ?>">
								<?php echo the_post_thumbnail('utheme-thumbnail', ['class' => 'display-xs-hidden']); ?>
								<?php echo the_post_thumbnail('post-thumbnail-small', ['class' => 'display-xs-visible']); ?>
                            </a>
                        </figure>
                    </article>
					<?php
					if ( $i % 2 ) {
						echo '</div><div>';
					}
					$i ++;
				endwhile;
				$r->reset_postdata();
				?>
            </div>
        </div>
	<?php
	else :
		get_template_part( 'template-parts/post/content', 'none' );
	endif;
	?>
</div>