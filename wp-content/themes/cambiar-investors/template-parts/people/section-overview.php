<?php
    $image               = get_field( 'image' );
    $position            = get_field( 'position' );
    $years_of_experience = get_field( 'years_of_experience' );
    $years_at_cambiar    = get_field( 'years_at_cambiar' );
?>
<section class="single-people-section">
    <div class="container">
        <a href="#" class="btn back-link btn-primary">Back</a>
        <div class="text-box text-white">
            <section-header class="section-header mod">
                <h1 class="title"><?php the_title(); ?></h1>
                <?php if ( $position ) : ?>
                    <h2><?php echo esc_attr( $position ); ?></h2>
                <?php endif; ?>
            </section-header>
            <div class="text-holder">
                <?php the_content(); ?>
            </div>
            <?php if ( $years_of_experience || $years_at_cambiar ) : ?>
                <div class="years-info">
	                <?php if ( $years_of_experience ) : ?>
                        <div>
                            <strong><?php echo esc_attr( $years_of_experience ); ?></strong>
                            <?php echo _n( 'year of experience', 'years of experience', $years_of_experience, 'utheme' ); ?>
                        </div>
	                <?php
                        endif;
                        
	                    if ( $years_at_cambiar ) :
                    ?>
                        <div>
                            <strong><?php echo esc_attr( $years_at_cambiar ); ?></strong>
		                    <?php echo _n( 'year at Cambiar', 'years at Cambiar', $years_at_cambiar, 'utheme' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
	    <?php if ( $image ) : ?>
            <div class="photo">
                <img src="<?php echo wp_get_attachment_image_url( $image, [ 500, 500 ] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
            </div>
	    <?php endif; ?>
        <div class="clearfix"></div>
    </div>
</section>