<?php
/**
 * Template Name: History
 *
 * @package		U_Theme/Template
 * @author 		uCAT
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="fp-block history-page" id="section1">
        <?php

        // check if the flexible content field has rows of data
        if( have_rows('slides') ):
            $i = 0;

            // loop through the rows of data
            while ( have_rows('slides') ) : the_row(); $i++;


                $link   = get_sub_field('link');
                $image1 = get_sub_field('image_1');
                $image2 = get_sub_field('image_2');

            ?>
                <div class="slide history-slide text-white" id="slide1_<?php echo $i; ?>" data-year="<?php the_sub_field('year'); ?>">
                    <div class="layer" data-depth="0.2">
                        <div class="year"><?php the_sub_field('year'); ?></div>
                    </div>
                    <?php
                    if( $image1 ) {
                        $image = wp_get_attachment_image_src($image1, 'history-thumbnail');
                        ?>
                        <div class="layer" data-depth="0.3">
                            <img src="<?php echo $image[0]; ?>"
                                 class="bg-img bottom left"
                                 width="576" height="256" alt="">
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                    if( $image2 ) {
                        $image = wp_get_attachment_image_src($image2, 'utheme-thumbnail');
                        ?>
                        <div class="layer" data-depth="0.4">
                            <img src="<?php echo $image[0]; ?>"
                                 class="bg-img center right"
                                 width="256" height="256" alt="">
                        </div>
                        <?php
                    }
                    ?>
                    <div class="layer" data-depth="0.0">
                        <div class="container">
                            <div class="title"><?php the_sub_field('title'); ?></div>
                            <div class="text">
                                <h2><?php the_sub_field('subtitle'); ?></h2>
                                <?php if( $link ){ ?>
                                    <p><a href="<?php echo $link; ?>"><?php _e('Learn More', 'utheme'); ?></a></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            <?php

            endwhile;

        else :

            // no layouts found

        endif;

        ?>
    </div>
<?php endwhile; // end of the loop. ?>

<?php get_footer();
