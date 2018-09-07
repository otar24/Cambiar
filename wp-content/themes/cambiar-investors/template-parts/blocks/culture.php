<?php $bg_type = get_sub_field( 'type' ); ?>
<div class="fp-block fp-auto-height">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="section-process section-hero bg-stretch bg-overlay-vertica<?php echo $bg_type === 'overlay' ? ' bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" <?php echo $bg ? ' style="background-image: url(\'' . $bg . '\')"' : ''; ?>></div>
	    <?php if ( $bg_type === 'overlay' ) : ?>
            <div class="fade-bg"></div>
	    <?php endif; ?>
        <div class="fp-container full-height mod">
            <div class="container">
                <div class="text-box text-white">
                    <div class="columns">
                        <div class="column-item text-holder">
                            <header class="section-header">
                                <h1 class="title text-uppercase h2"><?php the_sub_field( 'title' ); ?></h1>
                            </header>
                            <?php the_sub_field( 'description' ); ?>
                        </div>
                        <div class="column-item blockquote-item">
                            <div class="blockquote-box">
                                <div class="blockquote-img">
                                    <div class="holder">
                                        <?php
                                            if ( $author_image_id = get_sub_field( 'author_image' ) ) :
                                                echo wp_get_attachment_image( $author_image_id, [
                                                    200,
                                                    200
                                                ], false, [
                                                        'alt' => esc_attr( get_sub_field( 'author' ) )
                                                ] );
                                            endif;
                                        ?>
                                    </div>
                                </div>
                                <div class="blockquote-holder">
                                    <blockquote class="blockquote">
                                        <div class="blockquote-text">
                                            <?php the_sub_field( 'quote' ); ?>
                                        </div>
                                        <footer class="blockquote-footer">
                                            <strong><?php the_sub_field( 'author' ); ?></strong>
                                            <?php if ( $author_details = get_sub_field( 'author_details' ) ) : ?>
                                                <span><?php echo esc_attr( $author_details ); ?></span>
                                            <?php
                                                endif;

                                                if ( $other = get_sub_field( 'other' ) ) :
                                            ?>
                                                <span><?php echo esc_attr( $other ); ?></span>
                                            <?php endif; ?>
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ( $bottom_link = get_sub_field( 'bottom_link' ) ) : ?>
                        <div class="anhor-holder">
                            <a href="<?php echo esc_url( $bottom_link ); ?>" class="anhor-link">
                                <i class="icon-arrow-down-big"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>