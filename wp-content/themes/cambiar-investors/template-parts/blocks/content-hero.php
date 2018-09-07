<?php $bg_type = get_sub_field( 'type' ); ?>
<div class="fp-block fp-auto-height">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="section-hero bg-stretch-mod<?php echo $bg_type === 'overlay' ? ' bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" <?php echo $bg ? ' style="background-image: url(\'' . $bg . '\')"' : ''; ?>></div>
        <div class="fade-bg"></div>
        <div class="fp-container">
            <div class="container">
                <div class="text-box text-white">
                    <section-header class="section-header mod">
                        <h1 class="title text-uppercase">
                            <?php the_sub_field('title'); ?>
                        </h1>
                    </section-header>
                    <div class="text-holder">
                        <?php the_sub_field('description'); ?>
                    </div>
                    <div class="columns about-content">
                        <?php
                        $numrows = count( get_sub_field( 'content' ) );
                        while ( have_rows('content') ) : the_row();
                            if( $numrows > 1 ) echo '<div class="column-item">';
                                the_sub_field('column_content');
                            if( $numrows > 1 ) echo '</div>';
                        endwhile;
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </section>
</div>