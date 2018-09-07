<?php $bg_type = get_sub_field( 'type' ); ?>
<div class="fp-block <?php u_fp_auto_height(); ?>" data-anchor="home">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="section-hero bg-stretch bg-overlay-vertical<?php echo $bg_type === 'overlay' ? ' bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" style="background-image: url('<?php echo $bg; ?>')"></div>
        <?php if ( $bg_type === 'overlay' ) : ?>
            <div class="fade-bg"></div>
        <?php endif; ?>
        <div class="fp-container full-height">
            <div class="container">
                <div class="text-box text-white">
                    <header class="section-header">
                        <h1 class="title text-uppercase"><?php the_sub_field('title'); ?></h1>
                    </header>
                    <div class="text-holder">
                        <?php the_sub_field('description'); ?>
                    </div>
                    <div class="anhor-holder">
                        <a href="<?php the_sub_field('button_link'); ?>" class="anhor-link">
                            <i class="icon-arrow-down-big"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>