<?php $bg_type = get_sub_field( 'type' ); ?>
<div class="fp-block fp-auto-height" data-anchor="about">
    <section <?php echo $id ? ' id="' . $id . '"' : ''; ?> class="section-hero bg-stretch overlay<?php echo $bg_type === 'overlay' ? ' bg-overlay-mod' : ''; ?> <?php echo esc_attr( $class ); ?>">
        <div class="fp-bg" style="background-image: url('<?php echo $bg; ?>')"></div>
	    <?php if ( $bg_type === 'overlay' ) : ?>
            <div class="fade-bg"></div>
	    <?php endif; ?>
        <?php if( get_sub_field('full_width') ) : ?>
        <div class="video-box about-page text-white">
            <div class="u-yapi" data-url="<?php the_sub_field('youtube'); ?>" data-width="<?php echo ( $width = get_sub_field( 'video_width') ) ? $width : 0; ?>" data-height="<?php echo ( $height = get_sub_field( 'video_height') ) ? $height : 0; ?>">
                <div class="holder" >
                    <div class="img-holder">
                        <img src="<?php the_sub_field('cover_image'); ?>"  width="2880" height="1620" alt="CAMBIAR INVESTORS">
                    </div>
                    <a href="#" class="play-btn">
                        <img src="<?php echo u_get_assets_uri('images/play-btn.svg'); ?>" width="96" height="96" alt="image description">
                    </a>
                </div>
                <div class="video-text text-white">
                    <div class="title text-uppercase">
                        <p><?php _e('Explore Cambiar', 'utheme'); ?></p>
                    </div>
                    <div class="text">
                        <p><?php _e('Video description lorem ipsum dolor sit amet, consectetuer adipiscing elit.', 'utheme'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="fp-container">
            <div class="container mod">
                <div class="text-box text-white">
                    <header class="section-header mod">
                        <h1 class="title text-uppercase">
                            <?php the_sub_field('title'); ?>
                        </h1>
                    </header>
                    <div class="text-holder">
                        <?php the_sub_field('description'); ?>
                    </div>
                    <div class="video-box text-white" style="max-width:<?php echo ( $width = get_sub_field( 'video_width') ) ? $width.'px' : '100%'; ?>; max-height:<?php echo ( $height = get_sub_field( 'video_height') ) ? $height.'px' : 'auto'; ?>">
                        <div class="u-yapi" data-url="<?php the_sub_field('youtube'); ?>" data-width="<?php echo ( $width = get_sub_field( 'video_width') ) ? $width : 0; ?>" data-height="<?php echo ( $height = get_sub_field( 'video_height') ) ? $height : 0; ?>">
                            <div class="holder" >
                                <div class="img-holder">
                                    <img src="<?php the_sub_field('cover_image'); ?>" width="<?php echo ( $width = get_sub_field( 'video_width') ) ? $width : '1216'; ?>" height="<?php echo ( $height = get_sub_field( 'video_height') ) ? $height : '608'; ?>" alt="CAMBIAR INVESTORS">
                                </div>
                                <a href="#" class="play-btn">
                                    <img src="<?php echo u_get_assets_uri('images/play-btn.svg'); ?>" width="96" height="96" alt="image description">
                                </a>
                            </div>
                            <div class="video-text text-white">
                                <div class="title text-uppercase">
                                    <p><?php the_sub_field('video_title'); ?></p>
                                </div>
                                <div class="text">
                                    <p><?php the_sub_field('video_description'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ( get_sub_field( 'show_more_link' ) ) : ?>
                        <div class="btn-box display-xs-hidden">
                            <a href="<?php echo get_sub_field( 'link_url' ); ?>" class="btn btn-secondary text-uppercase"><?php echo get_sub_field( 'link_text' ); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>