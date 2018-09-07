<?php $bg_type = get_sub_field( 'type' ); ?>
<div class="fp-block fp-auto-height">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="bg-stretch bg-overlay-solid<?php echo $bg_type === 'overlay' ? ' bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" <?php echo $bg ? ' style="background-image: url(\'' . $bg . '\')"' : ''; ?>></div>
	    <?php if ( $bg_type === 'overlay' ) : ?>
            <div class="fade-bg"></div>
	    <?php endif; ?>
        <div class="fp-container full-height-center">
            <div class="container">
                <div class="text-box text-white">
                    <?php if ( $title = get_sub_field( 'title' ) ) : ?>
                        <header class="section-header">
                            <h1 class="title text-uppercase">
                                <?php echo esc_attr( $title ); ?>
                            </h1>
                        </header>
                    <?php
                        endif;

                        if( $desc = get_sub_field('description') ) :
                    ?>
                        <div class="text-holder">
                            <?php echo apply_filters( 'the_content', $desc ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="columns">
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