<?php
/**
 * Template Name: Full Page Parallax
 *
 * @package		U_Theme/Template
 * @author 		uCAT
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="fp-block international-page" id="section1">
        <?php

        // check if the flexible content field has rows of data
        if( have_rows('slides') ):
            $i = 0;

            // loop through the rows of data
            while ( have_rows('slides') ) : the_row(); $i++;
	            $full            = get_sub_field( 'fullwidth' );
	            $percentage_bg   = get_sub_field( 'percentage_bg' );
	            $background_url  = get_sub_field( 'background' );
	            $percent         = get_sub_field( 'percent' );
	            $description     = get_sub_field( 'description' );
	            $show_strategies = get_sub_field( 'show_strategies' );
	            $strategies      = get_sub_field( 'strategies' );
	            $bottom_text     = get_sub_field( 'bottom_text' );
                
                $bg = '';
                if( !$percentage_bg ){
                    $bg = $background_url ? 'style="background-image: url(' . $background_url .');"' : 'style="background: rgb(255, 255, 255);"';
                }

                $background_size = get_sub_field('background_size');

                $class = [];
                if($percentage_bg || $background_url){
                    $class[] = 'text-white';
                }
                if($background_size === 'cover'){
                    $class[] = 'bg-stretch';
                }
                ?>
                <div class="slide <?php echo implode(' ', $class); ?> slide<?php echo $i; ?><?php echo !$background_url && !$percentage_bg ? ' dark-nav' : ''; ?>" id="slide1_<?php echo $i; ?>" <?php echo $bg; ?>>
                    <?php if($percentage_bg) : ?>
                        <div class="bg-percentage">
                            <div class="count"><?php echo u_number_format($percent/100); ?></div>
                            <div class="shadow" style="width: <?php echo $percent; ?>%"></div>
                            <div class="shadow2" style="width: <?php echo 100-$percent; ?>%"></div>
                        </div>
                    <?php
                        endif;
                        
                        if ( $description || $bottom_text || ( $show_strategies && $strategies ) ) :
                    ?>
                        <div class="container <?php echo !$full ? 'narrow-text-width' : ''; ?>">
                            <?php if ( $description || ( $show_strategies && $strategies ) ) : ?>
                                <div class="text">
                                    <?php echo apply_filters( 'the_content', $description ); ?>
	                                <?php if ( $show_strategies && $strategies ) : ?>
		                                <?php require locate_template( 'template-parts/strategy/strategies-table.php' ); ?>
	                                <?php endif; ?>
                                </div>
                            <?php
                                endif;
	
	                            if ( $bottom_text ) :
                            ?>
                                <div class="bottom-text">
	                                <?php echo apply_filters( 'the_content', $bottom_text ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
